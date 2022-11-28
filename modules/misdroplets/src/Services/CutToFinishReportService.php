<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Services;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\HourWiseFinishingProduction;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;

class CutToFinishReportService
{
    private $buyerId;
    private $orderId;
    private $startDate;
    private $endDate;
    private $dateWiseProductions;
    private $finishingProductions;
    private $totalProductions;
    private $poDetails;

    private function __construct($buyerId, $orderId, $startDate, $endDate)
    {
        $this->buyerId = $buyerId;
        $this->orderId = $orderId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        return $this;
    }

    private function generateDateWiseProduction()
    {
        $this->dateWiseProductions = DateAndColorWiseProduction::query()
            ->selectRaw('*,SUM(cutting_qty) as total_cutting_qty,' .
                'SUM(print_sent_qty) as total_print_sent_qty,' .
                'SUM(print_received_qty) as total_print_received_qty,' .
                'SUM(embroidary_sent_qty) as total_embroidary_sent_qty,' .
                'SUM(embroidary_received_qty) as total_embroidary_received_qty,' .
                'SUM(input_qty) as total_input_qty,' .
                'SUM(sewing_output_qty) as total_sewing_output_qty'
            )
            ->with([
                'buyerWithoutGlobalScopes',
                'orderWithoutGlobalScopes',
                'purchaseOrderWithoutGlobalScopes',
                'colorWithoutGlobalScopes',
            ])
            ->when($this->buyerId, function ($query) {
                $query->where('buyer_id', $this->buyerId);
            })
            ->when($this->orderId, function ($query) {
                $query->where('order_id', $this->orderId);
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->where('production_date', '>=', $this->startDate)
                    ->where('production_date', '<=', $this->endDate);
            })
            ->groupBy(['purchase_order_id', 'color_id'])
            ->get();
    }

    private function generateTotalProduction()
    {
        $this->totalProductions = TotalProductionReport::query()
            ->whereIn('purchase_order_id', $this->dateWiseProductions->pluck('purchase_order_id'))
            ->whereIn('color_id', $this->dateWiseProductions->pluck('color_id'))
            ->get();
    }

    private function generateHourWiseFinishingProduction()
    {
        $hourSumString = '';
        for ($i = 0; $i < 24; $i++) {
            $hourSumString .= 'hour_' . $i . ($i == 23 ? '' : '+');
        }

        $this->finishingProductions = HourWiseFinishingProduction::query()
            ->selectRaw('buyer_id,order_id,po_id,color_id, production_type,' .
                'SUM(' . $hourSumString . ') as total_production')
            ->with(['color', 'order', 'buyer', 'purchaseOrder'])
            ->when($this->orderId, function ($query) {
                $query->where('order_id', $this->orderId);
            })
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->where('production_date', '>=', $this->startDate)
                    ->where('production_date', '<=', $this->endDate);
            })
            ->whereIn('production_type', [
                HourWiseFinishingProduction::IRON,
                HourWiseFinishingProduction::POLY,
                HourWiseFinishingProduction::PACKING
            ])
            ->groupBy(['po_id', 'color_id', 'production_type'])
            ->get();
    }

    private function getPODetails($poColors)
    {
        $allPO = collect($poColors)->keys();
        $colors = collect($poColors)->values()->collapse()->unique()->values();

        $this->poDetails = PurchaseOrderDetail::query()
            ->selectRaw('*,SUM(quantity) as total_qty')
            ->whereIn('purchase_order_id', $allPO)
            ->whereIn('color_id', $colors)
            ->groupBy(['purchase_order_id', 'color_id'])
            ->get();
    }

    private function format($poColors): array
    {
        $reportData = [];
        foreach ($poColors as $po => $colors) {
            foreach ($colors as $color_id) {
                $production = $this->dateWiseProductions
                    ->where('purchase_order_id', $po)
                    ->where('color_id', $color_id)
                    ->first();
                $totalProduction = $this->totalProductions
                    ->where('purchase_order_id', $po)
                    ->where('color_id', $color_id)
                    ->first();
                $finishingProduction = $this->finishingProductions
                    ->where('po_id', $po)
                    ->where('color_id', $color_id);
                $poDetail = $this->poDetails
                    ->where('purchase_order_id', $po)
                    ->where('color_id', $color_id)
                    ->first();

                $buyer = $production ?
                    $production->buyerWithoutGlobalScopes->name :
                    $finishingProduction->first()->buyer->name;
                $style = $production ?
                    $production->orderWithoutGlobalScopes->style_name :
                    $finishingProduction->first()->order->style_name;
                $purchaseOrder = $production ?
                    $production->purchaseOrderWithoutGlobalScopes->po_no :
                    $finishingProduction->first()->purchaseOrder->po_no;
                $color = $production ?
                    $production->colorWithoutGlobalScopes->name :
                    $finishingProduction->first()->color->name;

                $totalPoQty = $poDetail && $poDetail->total_qty > 0 ? $poDetail->total_qty : 0;
                $leftCutQty = $totalProduction->total_cutting - $totalPoQty;
                $leftCutPercent = ($leftCutQty / $totalPoQty) * 100;
                $todayIron = $finishingProduction->count() ? $finishingProduction
                    ->where('production_type', HourWiseFinishingProduction::IRON)
                    ->first()->total_production : 0;
                $todayPoly = $finishingProduction->count() ? $finishingProduction
                    ->where('production_type', HourWiseFinishingProduction::POLY)
                    ->first()->total_production : 0;
                $todayPacking = $finishingProduction->count() ? $finishingProduction
                    ->where('production_type', HourWiseFinishingProduction::PACKING)
                    ->first()->total_production : 0;

                $reportData[] = [
                    'buyer' => $buyer,
                    'style' => $style,
                    'purchase_order' => $purchaseOrder,
                    'color' => $color,
                    'color_wise_po' => $totalPoQty,
                    'today_cutting' => $production ? $production->total_cutting_qty : 0,
                    'total_cutting' => $totalProduction->total_cutting,
                    'left_qty' => $leftCutQty,
                    'left_percent' => round($leftCutPercent, 2),
                    'today_print_sent' => $production ? $production->total_print_sent_qty : 0,
                    'total_print_sent' => $totalProduction->total_sent,
                    'today_print_receive' => $production ? $production->total_print_received_qty : 0,
                    'total_print_receive' => $totalProduction->total_received,
                    'print_balance' => $totalProduction->total_received - $totalProduction->total_sent,
                    'today_embr_sent' => $production ? $production->total_embroidary_sent_qty : 0,
                    'total_embr_sent' => $totalProduction->total_embroidary_sent,
                    'today_embr_receive' => $production ? $production->total_embroidary_received_qty : 0,
                    'total_embr_receive' => $totalProduction->total_embroidary_received,
                    'embr_balance' => $totalProduction->total_embroidary_received - $totalProduction->total_embroidary_sent,
                    'today_input' => $production ? $production->total_input_qty : 0,
                    'total_input' => $totalProduction->total_input,
                    'input_balance' => $totalProduction->total_input - $totalProduction->total_cutting,
                    'today_output' => $production ? $production->total_sewing_output_qty : 0,
                    'total_output' => $totalProduction->total_sewing_output,
                    'output_balance' => $totalProduction->total_sewing_output - $totalProduction->total_input,
                    'today_iron' => $todayIron,
                    'total_iron' => $totalProduction->total_iron,
                    'iron_balance' => $totalProduction->total_iron - $totalProduction->total_sewing_output,
                    'today_poly' => $todayPoly,
                    'total_poly' => $totalProduction->total_poly,
                    'poly_balance' => $totalProduction->total_poly - $totalProduction->total_iron,
                    'today_packing' => $todayPacking,
                    'total_packing' => $totalProduction->total_packing,
                    'packing_balance' => $todayPacking - $todayPoly,
                ];
            }
        }
        return $reportData;
    }

    public static function make($buyerId, $orderId, $startDate, $endDate): self
    {
        return new static($buyerId, $orderId, $startDate, $endDate);
    }

    public function report(): array
    {
        $this->generateDateWiseProduction();
        if ($this->dateWiseProductions->count() == 0) {
            return [];
        }

        $this->generateTotalProduction();
        $this->generateHourWiseFinishingProduction();

        $poColors = $this->dateWiseProductions->groupBy('purchase_order_id')
            ->map(function ($collection) {
                return $collection->pluck('color_id');
            })->toArray();

        $this->finishingProductions->where('production_type', HourWiseFinishingProduction::PACKING)
            ->map(function ($collection) use (&$poColors) {
                if (!in_array($collection->color_id, $poColors[$collection->po_id])) {
                    $poColors[$collection->po_id][] = $collection->color_id;
                }
            });

        $this->getPODetails($poColors);

        return $this->format($poColors);
    }
}
