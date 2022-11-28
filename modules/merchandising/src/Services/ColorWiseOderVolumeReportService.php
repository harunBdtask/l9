<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualShipmentProduction;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class ColorWiseOderVolumeReportService
{
    private $buyerId;
    private $seasonId;
    private $fromDate;
    private $toDate;
    private $orderLeadTime;
    private $prodLeadTime;
    private $dateType;

    public function __construct(Request $request)
    {
        $this->buyerId = $request->input('buyer_id');
        $this->seasonId = $request->input('season_id');
        $this->orderLeadTime = $request->input('order_lead_time');
        $this->prodLeadTime = $request->input('prod_lead_time');
        $this->dateType = $request->input('date_type');

        $this->fromDate = Carbon::make($request->input('from_date', date('Y-m-d')))->format('Y-m-d');
        $this->toDate = Carbon::make($request->input('to_date', date('Y-m-d')))->format('Y-m-d');
    }

    public function report(): array
    {
        $reportData['total_po_fob'] = 0;
        $reportData['total_po_qty'] = 0;
        $reportData['total_po_fob_value'] = 0;
        $reportData['season'] = optional(Season::query()->find($this->seasonId))->season_name;

        $reportData['reports'] = Order::query()
            ->with([
                'factory',
                'purchaseOrders.poDetails',
                'purchaseOrders.tnaReport.task',
                'buyer',
                'season',
                'productDepartment',
                'tnaReport.task'
            ])
            ->whereHas('purchaseOrders', function ($query) {
                $query->when($this->orderLeadTime,
                    Filter::applyFilter('lead_time', $this->orderLeadTime))
                    ->when($this->prodLeadTime,
                        Filter::applyFilter('production_lead_time', $this->prodLeadTime))
                    ->when($this->dateType === 'po_receive_date',
                        Filter::applyBetweenFilter('po_receive_date', [$this->fromDate, $this->toDate]))
                    ->when($this->dateType === 'shipment_date',
                        Filter::applyBetweenFilter('ex_factory_date', [$this->fromDate, $this->toDate]))
                    ->when($this->dateType === 'pi_bunch_a_budget_date',
                        Filter::applyBetweenFilter('pi_bunch_budget_date', [$this->fromDate, $this->toDate]))
                    ->when($this->dateType === 'expected_bom_handover_date',
                        Filter::applyBetweenFilter('ex_bom_handover_date', [$this->fromDate, $this->toDate]))
                    ->when($this->dateType === 'fri_date', function ($q) {
                        $fri_date_from_date = $this->subAddOrFormatDate($this->fromDate, 2, 'add', 'Y-m-d');
                        $fri_date_to_date = $this->subAddOrFormatDate($this->toDate, 2, 'add', 'Y-m-d');
                        $q->where('ex_factory_date', '>=', $fri_date_from_date)
                            ->where('ex_factory_date', '<=', $fri_date_to_date);
                    })
                    ->when($this->dateType === 'rfs_date', function ($q) {
                        $rfs_date_from_date = $this->subAddOrFormatDate($this->fromDate, 1, 'add', 'Y-m-d');
                        $rfs_date_to_date = $this->subAddOrFormatDate($this->toDate, 1, 'add', 'Y-m-d');
                        $q->where('ex_factory_date', '>=', $rfs_date_from_date)
                            ->where('ex_factory_date', '<=', $rfs_date_to_date);
                    });
            })
            ->when($this->buyerId, function ($query) {
                $query->where('buyer_id', $this->buyerId);
            })
            ->when($this->seasonId, function (Builder $query) {
                $query->where('season_id', $this->seasonId);
            })
            ->get()
            ->map(function ($data) use (&$reportData) {
                $orderTNAPIBunchBudgetDate = collect($data->tnaReport)
                    ->where('task.task_name','PI Bunch Handover Date')
                    ->whereNotNull('start_date')
                    ->first();
                $orderTNAPIBunchBudgetActualDate = collect($data->tnaReport)
                    ->where('task.task_name','PI Bunch Handover Date')
                    ->whereNotNull('actual_start_date')
                    ->first();
                $orderTNABOMHandOverDate = collect($data->tnaReport)
                    ->where('task.task_name','BOM H/O Date')
                    ->whereNotNull('start_date')
                    ->first();
                $orderTNABOMHandOverActualDate = collect($data->tnaReport)
                    ->where('task.task_name','BOM H/O Date')
                    ->whereNotNull('actual_start_date')
                    ->first();
                $orderFRIDate = collect($data->tnaReport)
                    ->where('task.task_name','FRI Date')
                    ->whereNotNull('start_date')
                    ->first();
                $orderFRIActualDate = collect($data->tnaReport)
                    ->where('task.task_name','FRI Date')
                    ->whereNotNull('actual_start_date')
                    ->first();
                $orderRFSDate = collect($data->tnaReport)
                    ->where('task.task_name','RFS Date')
                    ->whereNotNull('start_date')
                    ->first();
                $orderRFSActualDate = collect($data->tnaReport)
                    ->where('task.task_name','RFS Date')
                    ->whereNotNull('actual_start_date')
                    ->first();
                $orderShipmentDate = collect($data->tnaReport)
                    ->where('task.task_name','HO To Ctg Date')
                    ->whereNotNull('start_date')
                    ->first();
                $orderShipmentActualDate = collect($data->tnaReport)
                    ->where('task.task_name','HO To Ctg Date')
                    ->whereNotNull('actual_start_date')
                    ->first();
                $orderRevisedShipmentDate = collect($data->tnaReport)
                    ->where('task.task_name','Revised Shipment Date')
                    ->whereNotNull('start_date')
                    ->first();
                $orderRevisedShipmentActualDate = collect($data->tnaReport)
                    ->where('task.task_name','Revised Shipment Date')
                    ->whereNotNull('actual_start_date')
                    ->first();
                $actualShipmentQty = ManualShipmentProduction::query()
                    ->where('order_id',$data->id)
                    ->sum('production_qty');
                $shortAccessShipmentQty = ManualShipmentProduction::query()
                    ->where('order_id',$data->id)
                    ->sum('short_qty');
//                dump($actualShipmentQty);
                return [$data['po_no'] => collect($data->purchaseOrders)->map(function ($po) use (&$reportData, $data,
                    $orderTNAPIBunchBudgetDate,$orderTNAPIBunchBudgetActualDate,$orderTNABOMHandOverDate,
                    $orderTNABOMHandOverActualDate,$orderFRIDate,$orderFRIActualDate,$orderRFSDate,$orderRFSActualDate,
                    $orderShipmentDate,$orderShipmentActualDate,$orderRevisedShipmentDate,$orderRevisedShipmentActualDate,
                    $actualShipmentQty,$shortAccessShipmentQty) {
                    return $po->poDetails->flatmap(function ($poCollection) use ($po, $data, &$reportData,
                        $orderTNAPIBunchBudgetDate,$orderTNAPIBunchBudgetActualDate,$orderTNABOMHandOverDate,
                        $orderTNABOMHandOverActualDate,$orderFRIDate,$orderFRIActualDate,$orderRFSDate,$orderRFSActualDate,
                        $orderShipmentDate,$orderShipmentActualDate,$orderRevisedShipmentDate,$orderRevisedShipmentActualDate,
                        $actualShipmentQty,$shortAccessShipmentQty) {
                        $poTNAPIBunchBudgetDate = collect($po->tnaReport)
                            ->where('task.task_name','PI Bunch Handover Date')
                            ->whereNotNull('start_date')
                            ->first();
                        $poTNAPIBunchBudgetActualDate = collect($po->tnaReport)
                            ->where('task.task_name','PI Bunch Handover Date')
                            ->whereNotNull('actual_start_date')
                            ->first();
                        $poTNABOMHandOverDate = collect($po->tnaReport)
                            ->where('task.task_name','BOM H/O Date')
                            ->whereNotNull('start_date')
                            ->first();
                        $poTNABOMHandOverActualDate = collect($po->tnaReport)
                            ->where('task.task_name','BOM H/O Date')
                            ->whereNotNull('actual_start_date')
                            ->first();
                        $poTNAFRIDate = collect($po->tnaReport)
                            ->where('task.task_name','FRI Date')
                            ->whereNotNull('start_date')
                            ->first();
                        $poTNAFRIActualDate = collect($po->tnaReport)
                            ->where('task.task_name','FRI Date')
                            ->whereNotNull('actual_start_date')
                            ->first();
                        $poTNARFSDate = collect($data->tnaReport)
                            ->where('task.task_name','RFS Date')
                            ->whereNotNull('start_date')
                            ->first();
                        $poTNARFSActualDate = collect($po->tnaReport)
                            ->where('task.task_name','FRI Date')
                            ->whereNotNull('actual_start_date')
                            ->first();
                        $poTNAShipmentDate = collect($data->tnaReport)
                            ->where('task.task_name','HO To Ctg Date')
                            ->whereNotNull('start_date')
                            ->first();
                        $poTNAShipmentActualDate = collect($po->tnaReport)
                            ->where('task.task_name','HO To Ctg Date')
                            ->whereNotNull('actual_start_date')
                            ->first();
                        $poTNARevisedShipmentDate = collect($data->tnaReport)
                            ->where('task.task_name','Revised Shipment Date')
                            ->whereNotNull('start_date')
                            ->first();
                        $poTNARevisedShipmentActualDate = collect($po->tnaReport)
                            ->where('task.task_name','Revised Shipment Date')
                            ->whereNotNull('actual_start_date')
                            ->first();
                        return collect($poCollection->colors)->map(function ($color) use ($poCollection, $po, $data, &$reportData,
                            $poTNAPIBunchBudgetDate,$poTNAPIBunchBudgetActualDate,$orderTNAPIBunchBudgetActualDate,$orderTNAPIBunchBudgetDate,
                            $poTNABOMHandOverDate,$poTNABOMHandOverActualDate,$orderTNABOMHandOverDate,$orderTNABOMHandOverActualDate,
                            $orderFRIDate,$orderFRIActualDate,$poTNAFRIDate,$poTNAFRIActualDate,$orderRFSDate,$orderRFSActualDate,
                            $poTNARFSDate,$poTNARFSActualDate,$orderShipmentDate,$orderShipmentActualDate,$poTNAShipmentDate,$poTNAShipmentActualDate,
                            $orderRevisedShipmentDate,$orderRevisedShipmentActualDate,$poTNARevisedShipmentDate,$poTNARevisedShipmentActualDate,
                            $actualShipmentQty,$shortAccessShipmentQty) {
                            $quantityMatrix = collect($poCollection->quantity_matrix)
                                ->where('particular', PurchaseOrder::QTY)
                                ->where('color_id', $color);

                            $quantityPerPcs = $quantityMatrix->sum('value') ?? 0;
                            $colorName = $quantityMatrix->first();
                            $avgRate = is_numeric($po->avg_rate_pc_set) ? $po->avg_rate_pc_set : 0;
                            $totalValue = $quantityPerPcs * $avgRate;
                            $reportData['buyer'] = $data->buyer->name;
                            $reportData['buyer_id'] = $data->buyer_id;
                            $reportData['company'] = $data->factory->factory_name;
                            $reportData['total_po_qty'] += (double)$quantityPerPcs;
                            $reportData['total_po_fob_value'] += (double)$totalValue;
                            $reportData['total_po_fob'] += (double)$avgRate;

                            $actual_shipment_date = $this->subAddOrFormatDate($po->ex_factory_date);
                            $factory_shipment_date = $this->subAddOrFormatDate($po->country_ship_date);

                            $uom = ($data->order_uom_id && ((int)$data->order_uom_id < 3))
                                ? PriceQuotation::STYLE_UOM[$data->order_uom_id]
                                : PriceQuotation::STYLE_UOM['1'];

                            $piBunchBudgetDate = ($poTNAPIBunchBudgetDate ? $poTNAPIBunchBudgetDate->finish_date : null) ??
                                ($orderTNAPIBunchBudgetDate ? $orderTNAPIBunchBudgetDate->finish_date : null);

                            $piBunchBudgetActualDate = ($poTNAPIBunchBudgetActualDate ? $poTNAPIBunchBudgetActualDate->actual_finish_date : null) ??
                                ($orderTNAPIBunchBudgetActualDate ? $orderTNAPIBunchBudgetActualDate->actual_finish_date : null);

                            $orderOrPIHandOverDate = ($orderTNABOMHandOverDate ? $orderTNABOMHandOverDate->finish_date : null) ??
                                ($poTNABOMHandOverDate ? $poTNABOMHandOverDate->finish_date : null);

                            $orderOrPIHandOverActualDate = ($orderTNABOMHandOverActualDate ? $orderTNABOMHandOverActualDate->actual_finish_date : null) ??
                                ($poTNABOMHandOverActualDate ? $poTNABOMHandOverActualDate->actual_finish_date : null);

                            $orderOrPIFRIDate = ($orderFRIDate ? $orderFRIDate->finish_date : null) ??
                                ($poTNAFRIDate ? $poTNAFRIDate->finish_date : null);

                            $orderOrPIFRIActualDate = ($orderFRIActualDate ? $orderFRIActualDate->actual_finish_date : null) ??
                                ($poTNAFRIActualDate ? $poTNAFRIActualDate->actual_finish_date : null);

                            $orderOrPIRFSDate = ($orderRFSDate ? $orderRFSDate->finish_date : null) ??
                                ($poTNARFSDate ? $poTNARFSDate->finish_date : null);

                            $orderOrPIRFSActualDate = ($orderRFSActualDate ? $orderRFSActualDate->actual_finish_date : null) ??
                                ($poTNARFSActualDate ? $poTNARFSActualDate->actual_finish_date : null);

                            $orderOrPIShipmentDate = ($orderShipmentDate ? $orderShipmentDate->finish_date : null) ??
                                ($poTNAShipmentDate ? $poTNAShipmentDate->finish_date : null);

                            $orderOrPIShipmentActualDate = ($orderShipmentActualDate ? $orderShipmentActualDate->actual_finish_date : null) ??
                                ($poTNAShipmentActualDate ? $poTNAShipmentActualDate->actual_finish_date : null);

                            $orderOrPIRevisedShipmentDate = ($orderRevisedShipmentDate ? $orderRevisedShipmentDate->finish_date : null) ??
                                ($poTNARevisedShipmentDate ? $poTNARevisedShipmentDate->finish_date : null);

                            $orderOrPIRevisedShipmentActualDate = ($orderRevisedShipmentActualDate ? $orderRevisedShipmentActualDate->actual_finish_date : null) ??
                                ($poTNARevisedShipmentActualDate ? $poTNARevisedShipmentActualDate->actual_finish_date : null);

                            return [
                                'season' => $data->season->season_name ?? null,
                                'buyer' => $data->buyer->name,
                                'unique_id' => $data->job_no,
                                'style' => $data->style_name,
                                'dealing_merchant' => $data->dealingMerchant->screen_name,
                                'image' => $data->images,
                                'product_dept' => $data->productDepartment->product_department,
                                'uom' => $uom,
                                'po' => $po->po_no,
                                'po_qty' => $quantityPerPcs,
                                'po_fob' => $avgRate,
                                'actual_ship_date' => $actual_shipment_date,
                                'factory_ship_date' => $factory_shipment_date,
                                'po_receive_date' => $this->subAddOrFormatDate($po->po_receive_date),
                                'po_fob_value' => $totalValue,
                                'remarks' => $po->remarks,
                                'order_status' => $po->order_status,
                                'smv' => $data->smv,
                                'color' => $colorName['color'] ?? null,
                                'group' => $data->garments_item_group,
                                'fab_type' => $data->fabric_type,
                                'fabric_composition' => $data->fabric_composition,
                                'order_rcv_date' => $this->subAddOrFormatDate($po->po_receive_date),
                                'country_ship_date' => $this->subAddOrFormatDate($po->country_ship_date),
                                'shipment_date' => $orderOrPIShipmentDate,
                                'shipment_actual_date' => $orderOrPIShipmentActualDate,
                                'shipment_date_deviation_days' => $orderOrPIShipmentDate && $orderOrPIShipmentActualDate
                                    ? Carbon::make($orderOrPIShipmentDate)->diffInDays($orderOrPIShipmentActualDate)
                                    : null,
                                'lead_time' => $po->lead_time,
                                'production_lead_time' => $po->production_lead_time,
                                'pi_bunch_budget_date' => $piBunchBudgetDate,
                                'pi_bunch_budget_actual_date' => $piBunchBudgetActualDate,
                                'pi_bunch_budget_date_deviation_days' => $piBunchBudgetDate && $piBunchBudgetActualDate
                                    ? Carbon::make($piBunchBudgetDate)->diffInDays($piBunchBudgetActualDate)
                                    : null,
                                'bom_handover_date' => $orderOrPIHandOverDate,
                                'bom_handover_actual_date' => $orderOrPIHandOverActualDate,
                                'order_handover_date_deviation_days' => $orderOrPIHandOverDate && $orderOrPIHandOverActualDate
                                    ? Carbon::make($orderOrPIHandOverDate)->diffInDays($orderOrPIHandOverActualDate)
                                    : null,
                                'fri_date' => $orderOrPIFRIDate,
                                'fri_actual_date' => $orderOrPIFRIActualDate,
                                'fri_date_deviation_days' => $orderOrPIFRIDate && $orderOrPIFRIActualDate
                                    ? Carbon::make($orderOrPIFRIDate)->diffInDays($orderOrPIFRIActualDate)
                                    : null,
                                'rfs_date' => $orderOrPIRFSDate,
                                'rfs_actual_date' => $orderOrPIRFSActualDate,
                                'rfs_date_deviation_days' => $orderOrPIRFSDate && $orderOrPIRFSActualDate
                                    ? Carbon::make($orderOrPIRFSDate)->diffInDays($orderOrPIRFSActualDate)
                                    : null,
                                'revised_shipment_date' => $orderOrPIRevisedShipmentDate,
                                'revised_shipment_actual_date' => $orderOrPIRevisedShipmentActualDate,
                                'revised_shipment_date_deviation_days' => $orderOrPIRevisedShipmentDate && $orderOrPIRevisedShipmentActualDate
                                    ? Carbon::make($orderOrPIRevisedShipmentDate)->diffInDays($orderOrPIRevisedShipmentActualDate)
                                    : null,
                                'actual_shipment_qty' => $actualShipmentQty,
                                'short_access_shipment_quantity' => $shortAccessShipmentQty,
                                'print_status' => $po->print_status,
                                'embroidery_status' => $po->embroidery_status,
                                'item' => $data->item_details,
                                'created_at' => $po->created_at ? Carbon::make($po->created_at)->format('d/m/Y H:ia') : null,
                                'created_by' => $po->createdBy->screen_name,
                                'total_amount' => $quantityPerPcs * $avgRate,
                            ];
                        });
                    });
                })];
            })->collapse()->collapse()->collapse()->groupBy(['group', 'style'])->toArray();

        return $reportData;
    }

    private function subAddOrFormatDate($date, $day = null, $type = null, $format = 'd/m/Y'): string
    {
        $formattedDate = '';
        if ($date) {
            $formattedDate = Carbon::parse($date);
            if ($day && $type == 'add') {
                $formattedDate = $formattedDate->addDays($day);
            }

            if ($day && $type == 'sub') {
                $formattedDate = $formattedDate->subDays($day);
            }
            $formattedDate = $formattedDate->format($format);
        }

        return $formattedDate;
    }
}
