<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Iedroplets\Models\Shipment;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\Knitting\Models\KnitProgramRoll;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\StyleAuditReport;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrderDetail;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\CostingSheetService;

class StyleAuditReportAction
{
    private $styleAuditReport, $orderId;

    /**
     * @param $orderId
     * @return $this
     */
    public function init($orderId): StyleAuditReportAction
    {
        $this->orderId = $orderId;

        $this->styleAuditReport = StyleAuditReport::query()->where('style_id', $this->orderId)->first();
        if (!$this->styleAuditReport) {
            $this->styleAuditReport = new StyleAuditReport();
            $this->styleAuditReport->style_id = $this->orderId;
            $this->saveOrUpdate();
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function handleOrder(): StyleAuditReportAction
    {
        $purchaseOrderSum = PurchaseOrder::query()
            ->selectRaw(DB::raw('SUM(po_quantity) AS totalOrderQty, SUM(po_quantity*avg_rate_pc_set) AS totalOrderValue'))
            ->groupBy('order_id')
            ->where('order_id', $this->orderId)
            ->get();
        $this->styleAuditReport->order_qty = isset($purchaseOrderSum) ? $purchaseOrderSum->sum('totalOrderQty') : 0;
        $this->styleAuditReport->order_value = isset($purchaseOrderSum) ? $purchaseOrderSum->sum('totalOrderValue') : 0;

        return $this;
    }

    /**
     * @return $this
     */
    public function handleBudget(): StyleAuditReportAction
    {
        $budget = Budget::query()
            ->with([
                'costings',
                'fabricCosting',
                'trimCosting',
                'embellishmentCosting',
                'commercialCosting',
            ])
            ->where('copy_from_id', $this->orderId)
            ->first();

        if (isset($budget->costings)) {
            if (isset($budget->fabricCosting)) {
                $otherProcessCost = CostingSheetService::formatOtherProcessCostData($budget);
                $yarnCostData = CostingSheetService::formatYarnCostData($budget);
                $knitCostData = CostingSheetService::formatKnitCostData($budget);
                $dyingCostData = CostingSheetService::formatDyingCostData($budget);
                $grandFabricCost = count($otherProcessCost) ? collect($otherProcessCost)->sum('total_amount') : 0;
                $grandFabricCost = $grandFabricCost + (count($yarnCostData) ? collect($yarnCostData)->sum('total_amount') : 0);
                $grandFabricCost = $grandFabricCost + (count($knitCostData) ? collect($knitCostData)->sum('total_amount')  : 0);
                $grandFabricCost = $grandFabricCost + (count($dyingCostData) ? collect($dyingCostData)->sum('total_amount') : 0);
            }

            $otherCosting = CostingSheetService::formatLabCostData($budget);
            $trimsCostData = isset($budget->trimCosting) ? CostingSheetService::formatTrimsCostData($budget) : [];
            $totalEmblCost = CostingSheetService::formatEmbellishmentCostData($budget)['totalEmblCost'] ?? 0;
            $totalCommercialCost = CostingSheetService::formatCommercialCostData($budget)['totalCommercialAmount'] ?? 0;
            $totalOtherCost = collect($otherCosting)->sum('amount') ?? 0;
            $totalTrimsCost = collect($trimsCostData)->sum('total_amount') ?? 0;


            $budgetFabricCosting = $budget->fabricCosting ? $budget->fabricCosting['details'] : [];

            $budgetTrimsCosting = $budget->trimCosting ? $budget->trimCosting['details'] : [];
            $this->styleAuditReport->fabric_req_qty = count($budgetFabricCosting) > 0 ? collect($budgetFabricCosting['details']['fabricForm'])
                ->groupBy('uom')
                ->map(function ($collection) {
                    return [
                        "uom_id" => $collection->first()['uom'],
                        "uom_value" => BudgetService::UOM[$collection->first()['uom']],
                        "qty" => $collection->sum('grey_cons_total_quantity')
                    ];
                })->values()->toArray() : [];

            $this->styleAuditReport->fabric_cost_value = $budgetFabricCosting['calculation']['fabric_costing']['grey_cons_total_amount_sum'] ?? 0;

            $this->styleAuditReport->trims_cost_Value = $budgetTrimsCosting['calculation']['total_amount_sum'] ?? 0;

            $this->styleAuditReport->others_cost = $totalOtherCost;

            $this->styleAuditReport->budget_value = ($grandFabricCost ?? 0) + $totalEmblCost + $totalCommercialCost
                + $totalOtherCost + $totalTrimsCost;
        }

        return $this;
    }

    public function handleFabricBooking(): StyleAuditReportAction
    {
        $detailsBreakdown = FabricBookingDetailsBreakdown::query()
            ->whereHas('order', function ($builder) {
                return $builder->where('id', $this->orderId);
            })
            ->selectRaw(DB::raw('*,SUM(actual_wo_qty) AS totalActualWoQty,SUM(amount) AS totalAmount'))
            ->groupBy('uom')
            ->get();

        $shortDetailsBreakdown = ShortFabricBookingDetailsBreakdown::query()
            ->whereHas('order', function ($builder) {
                return $builder->where('id', $this->orderId);
            })
            ->selectRaw(DB::raw('*,SUM(actual_wo_qty) AS totalActualWoQty,SUM(amount) AS totalAmount'))
            ->groupBy('uom')
            ->get();

        if ($detailsBreakdown && $shortDetailsBreakdown) {
            $this->styleAuditReport->fabric_booked_qty = $detailsBreakdown->map(function ($collection) use ($shortDetailsBreakdown) {
                $shortDetailsQty = $shortDetailsBreakdown->where('uom', $collection->uom)->sum('totalActualWoQty');
                return [
                    "uom_id" => $collection->uom,
                    "uom_value" => $collection->uom_value,
                    "qty" => $collection->totalActualWoQty + $shortDetailsQty,
                ];
            })->values()->toArray();

            $this->styleAuditReport->fabric_booked_value =
                $detailsBreakdown->sum('totalAmount') + $shortDetailsBreakdown->sum('totalAmount');

        }
        return $this;
    }

    public function handleYarnPurchase(): StyleAuditReportAction
    {
        $yarnPurchaseDetail = YarnPurchaseOrderDetail::query()
            ->selectRaw('*, SUM(wo_qty) AS total_wo_qty,SUM(amount) AS total_value')
            ->whereHas('order', function ($query) {
                return $query->where('id', $this->orderId);
            })
            ->groupBy('uom_id')
            ->get();

        $this->styleAuditReport->yarn_issue_qty = isset($yarnPurchaseDetail) ? $yarnPurchaseDetail->map(function ($collection) {
            return [
                "uom_id" => $collection->uom_id,
                "uom_value" => $collection->unitOfMeasurement->unit_of_measurement ?? null,
                "qty" => $collection->total_wo_qty,
            ];
        })->values()->toArray() : [];
        $this->styleAuditReport->yarn_issue_value = isset($yarnPurchaseDetail) ? $yarnPurchaseDetail->sum('total_value') : 0;
        return $this;
    }

    public function handleKnitting(): StyleAuditReportAction
    {
        $this->styleAuditReport->knitting_qty = KnitProgramRoll::query()
                ->whereHas('planningInfo.order', function ($collection) {
                    return $collection->where('id', $this->orderId);
                })
                ->sum('roll_weight') ?? 0;
        return $this;
    }

    public function handleFinishFabric(): StyleAuditReportAction
    {
        $finishFabricDetails = FabricReceiveDetail::query()
            ->selectRaw(DB::raw('*,SUM(receive_qty) AS totalQty,SUM(amount) AS totalValue'))
            ->where('style_id', $this->orderId)
            ->groupBy('uom_id')
            ->get();

        if (isset($finishFabricDetails)) {
            $this->styleAuditReport->finish_fab_qty = $finishFabricDetails->map(function ($collection) {
                    return [
                        "uom_id" => $collection->uom_id,
                        "uom_value" => $collection->uom->unit_of_measurement ?? null,
                        "qty" => $collection->totalQty,
                    ];
                }) ?? [];
            $this->styleAuditReport->finish_fab_value = $finishFabricDetails->sum('totalValue') ?? 0;
        }

        return $this;
    }

    public function handleProduction(): StyleAuditReportAction
    {
        $queryString = ["*,SUM(total_cutting) AS totalCuttingQty",
            "SUM(total_sent) AS totalPrintSentQty",
            "SUM(total_embroidary_sent) AS totalEmbrSentQty",
            "SUM(total_received) AS totalPrintReceivedQty",
            "SUM(total_embroidary_received) AS totalEmbrReceivedQty",
            "SUM(total_input) AS totalInputQty",
            "SUM(total_sewing_output) AS totalSewingOutputQty",
            "SUM(total_poly) AS totalPolyQty",
            "SUM(total_iron) AS totalIronQty",
            "SUM(total_packing) AS totalPackingQty"];
        $queryString = implode(",", $queryString);

        $production = TotalProductionReport::query()
            ->with('purchaseOrder')
            ->selectRaw(DB::raw($queryString))
            ->where('order_id', $this->orderId)
            ->groupBy('order_id', "purchase_order_id")
            ->get();

        if (isset($production)) {
            $production = $production->map(function ($collection) {
                $fob_price = $collection->purchaseOrder ? $collection->purchaseOrder['avg_rate_pc_set'] : 0;
                $collection['totalCuttingValue'] = $collection->totalCuttingQty * $fob_price;
                $collection['totalPrintSentValue'] = $collection->totalPrintSentQty * $fob_price;
                $collection['totalPrintReceivedValue'] = $collection->totalPrintReceivedQty * $fob_price;
                $collection['totalEmbrSentValue'] = $collection->totalEmbrSentQty * $fob_price;
                $collection['totalEmbrReceivedValue'] = $collection->totalEmbrReceivedQty * $fob_price;
                $collection['totalInputValue'] = $collection->totalInputQty * $fob_price;
                $collection['totalSewingOutputValue'] = $collection->totalSewingOutputQty * $fob_price;
                $collection['totalPolyValue'] = $collection->totalPolyQty * $fob_price;
                $collection['totalIronValue'] = $collection->totalIronQty * $fob_price;
                $collection['totalPackingValue'] = $collection->totalPackingQty * $fob_price;
                return $collection;
            });

            $this->styleAuditReport->cutting_qty = $production->sum('totalCuttingQty') ?? 0;
            $this->styleAuditReport->cutting_value = $production->sum('totalCuttingValue') ?? 0;
            $this->styleAuditReport->print_sent_qty = $production->sum('totalPrintSentQty') ?? 0;
            $this->styleAuditReport->print_sent_value = $production->sum('totalPrintSentValue') ?? 0;
            $this->styleAuditReport->print_receive_qty = $production->sum('totalPrintReceivedQty') ?? 0;
            $this->styleAuditReport->print_receive_value = $production->sum('totalPrintReceivedValue') ?? 0;
            $this->styleAuditReport->embr_sent_qty = $production->sum('totalEmbrSentQty') ?? 0;
            $this->styleAuditReport->embr_sent_value = $production->sum('totalEmbrSentValue') ?? 0;
            $this->styleAuditReport->embr_receive_qty = $production->sum('totalEmbrReceivedQty') ?? 0;
            $this->styleAuditReport->embr_receive_value = $production->sum('totalEmbrReceivedValue') ?? 0;
            $this->styleAuditReport->input_qty = $production->sum('totalInputQty') ?? 0;
            $this->styleAuditReport->input_value = $production->sum('totalInputValue') ?? 0;
            $this->styleAuditReport->sewing_qty = $production->sum('totalSewingOutputQty') ?? 0;
            $this->styleAuditReport->sewing_value = $production->sum('totalSewingOutputValue') ?? 0;
            $this->styleAuditReport->poly_qty = $production->sum('totalPolyQty') ?? 0;
            $this->styleAuditReport->poly_value = $production->sum('totalPolyValue') ?? 0;
            $this->styleAuditReport->iron_qty = $production->sum('totalIronQty') ?? 0;
            $this->styleAuditReport->iron_value = $production->sum('totalIronValue') ?? 0;
            $this->styleAuditReport->packing_qty = $production->sum('totalPackingQty') ?? 0;
            $this->styleAuditReport->packing_value = $production->sum('totalPackingValue') ?? 0;
        }

        return $this;
    }

    public function handleShipment(): StyleAuditReportAction
    {
        $shipment = Shipment::query()
            ->selectRaw(DB::raw('*,SUM(ship_quantity) AS totalShipmentQty'))
            ->where('order_id', $this->orderId)
            ->groupBy('order_id', 'purchase_order_id')
            ->get()->map(function ($collection) {
                $fob_price = $collection->purchaseOrder['avg_rate_pc_set'];
                $collection['totalShipmentValue'] = $collection['totalShipmentQty'] * $fob_price;
                return $collection;
            });
        $this->styleAuditReport->shipment_qty = $shipment->sum('totalShipmentQty') ?? 0;
        $this->styleAuditReport->shipment_value = $shipment->sum('totalShipmentValue') ?? 0;

        return $this;
    }

    public function saveOrUpdate(): string
    {
        $this->styleAuditReport->save();
        return 'Success';
    }
}
