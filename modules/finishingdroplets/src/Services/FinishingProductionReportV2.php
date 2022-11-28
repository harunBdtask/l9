<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Services;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\HourWiseFinishingProduction;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;

class FinishingProductionReportV2
{
    protected $date, $buyer, $ironFinishData, $receivedData;

    public function __construct($date, $buyer = null)
    {
        $this->date = $date;
        $this->buyer = $buyer;
    }

    public function generateReport(): array
    {
        $this->ironFinishData = $this->ironFinishDataGenerator();
        $this->receivedData = $this->receivedDataGenerator();

        return $this->format();
    }

    public function ironFinishDataGenerator()
    {
        return HourWiseFinishingProduction::query()
            ->with(['floor', 'buyer', 'order', 'purchaseOrder', 'color'])
            ->selectRaw(DB::raw("*,SUM(hour_0+hour_1+hour_2+hour_3+hour_4+hour_5+hour_6+hour_7+hour_8+hour_9+hour_10+hour_11+hour_12+hour_13+hour_14+hour_15+hour_16+hour_17+hour_18+hour_19+hour_20+hour_21+hour_22+hour_23) AS total_hour_production"))
            ->whereIn('production_type',
                [HourWiseFinishingProduction::PACKING, HourWiseFinishingProduction::IRON]
            )
            ->when($this->buyer, function ($q) {
                return $q->where('buyer_id', $this->buyer);
            })
            ->whereDate('production_date', '<=', $this->date)
            ->groupBy(['production_date', 'order_id', 'po_id', 'color_id', 'production_type'])
            ->get();
    }

    public function receivedDataGenerator()
    {
        $allPos = collect($this->ironFinishData)->pluck('po_id')->unique()->values();
        $allColors = collect($this->ironFinishData)->pluck('color_id')->unique()->values();
        return DateAndColorWiseProduction::query()
            ->selectRaw(DB::raw("*,SUM(sewing_output_qty) AS total_sewing_production"))
            ->when($this->buyer, function ($q) {
                return $q->where('buyer_id', $this->buyer);
            })
            ->whereIn('purchase_order_id', $allPos)
            ->whereIn('color_id', $allColors)
            ->where('sewing_output_qty', '>', 0)
            ->whereDate('production_date', '<=', $this->date)
            ->groupBy(['production_date', 'order_id', 'purchase_order_id', 'color_id'])
            ->get();

    }

    public function format(): array
    {
        return collect($this->ironFinishData)
            ->where('production_date', $this->date)
            ->where('production_type', HourWiseFinishingProduction::IRON)
            ->map(function ($collection) {

                $orderQty = PurchaseOrderDetail::getColorWisePoQuantity($collection->po_id, $collection->color_id);
                $orderQtyAddingOnePercent = $orderQty + ($orderQty * 0.1);

                $filteredReceived = collect($this->receivedData)
                    ->where('order_id', $collection->order_id)
                    ->where('purchase_order_id', $collection->po_id)
                    ->where('color_id', $collection->color_id);

                $filteredIronPackingData = collect($this->ironFinishData)
                    ->where('order_id', $collection->order_id)
                    ->where('po_id', $collection->po_id)
                    ->where('color_id', $collection->color_id);

                $dailyReceivedQty = $filteredReceived
                    ->where('production_date', $this->date)
                    ->sum('total_sewing_production');
                $preReceivedQty = $filteredReceived
                    ->where('production_date', '<', $this->date)
                    ->sum('total_sewing_production');

                $dailyPackingQty = $filteredIronPackingData->count() > 0 ? collect($filteredIronPackingData)
                    ->where('production_type', HourWiseFinishingProduction::PACKING)
                    ->where('production_date', $this->date)
                    ->sum('total_hour_production') : 0;

                $prePackingQty = $filteredIronPackingData->count() > 0 ? collect($filteredIronPackingData)
                    ->where('production_type', HourWiseFinishingProduction::PACKING)
                    ->where('production_date', '<', $this->date)
                    ->sum('total_hour_production') : 0;

                $preIronQty = $filteredIronPackingData->count() > 0 ? collect($filteredIronPackingData)
                    ->where('production_type', HourWiseFinishingProduction::IRON)
                    ->where('production_date', '<', $this->date)
                    ->sum('total_hour_production') : 0;

                return [
                    'buyer' => $collection->buyer->name,
                    'style' => $collection->order->style_name,
                    'po_no' => $collection->purchaseOrder->po_no,
                    'country' => $collection->purchaseOrder->country->name,
                    'color' => $collection->color->name,
                    'order_qty' => $orderQty,
                    'order_qty_one_percentage' => $orderQtyAddingOnePercent,
                    'daily_received' => $dailyReceivedQty,
                    'pre_received' => $preReceivedQty,
                    'total_received' => $dailyReceivedQty + $preReceivedQty,
                    'daily_iron' => $collection->total_hour_production ?? 0,
                    'pre_iron' => $preIronQty,
                    'total_iron' => ($collection->total_hour_production ?? 0) + $preIronQty,
                    'daily_finish' => $dailyPackingQty,
                    'pre_finish' => $prePackingQty,
                    'total_finish' => $dailyPackingQty + $prePackingQty,
                    'balance_qty' => ($dailyPackingQty + $prePackingQty) - $orderQty,
                    'finish_floor' => $filteredIronPackingData
                        ->pluck('floor.name')
                        ->unique()
                        ->implode(', '),
                ];
            })->toArray();
    }
}
