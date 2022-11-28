<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services;

use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class DailySizeWiseCuttingReportService
{
    protected $date, $floor_id;

    public function __construct($date, $floorId)
    {
        $this->date = $date;
        $this->floor_id = $floorId;
    }


    /**
     * @return array
     */
    public function report(): array
    {
        $cutProductionReportData = DateTableWiseCutProductionReport::query()
            ->with([
                'buyer',
                'orderWithoutGlobalScopes',
                'garmentsItem',
                'purchaseOrder',
                'purchaseOrderDetail:garments_item_id,color_id,quantity',
                'color',
                'size'
            ])->whereDate('production_date', $this->date)
            ->when($this->floor_id, function ($query) {
                $query->where('cutting_floor_id', $this->floor_id);
            })
            ->get();
        $cutProductionReportDataClone = clone $cutProductionReportData;
        $sizes = $cutProductionReportData->pluck('size_id')->unique();

        $reportData['sizes'] = Size::query()->whereIn('id', $sizes)->orderBy('sort', 'ASC')->get();

        $totalProductionReportData = TotalProductionReport::query()
            ->whereIn('buyer_id', $cutProductionReportData->pluck('buyer_id')->toArray())
            ->whereIn('order_id', $cutProductionReportData->pluck('order_id')->toArray())
            ->whereIn('garments_item_id', $cutProductionReportData->pluck('garments_item_id')->toArray())
            ->whereIn('purchase_order_id', $cutProductionReportData->pluck('purchase_order_id')->toArray())
            ->whereIn('color_id', $cutProductionReportData->pluck('color_id'))
            ->select(['buyer_id', 'order_id', 'garments_item_id', 'purchase_order_id', 'color_id', 'total_cutting'])
            ->get();

        $reportData['data'] = $cutProductionReportData
            ->groupBy(['buyer_id', 'order_id', 'garments_item_id', 'purchase_order_id', 'color_id'])
            ->flatMap(function ($buyerWise) use ($sizes, $totalProductionReportData, $cutProductionReportDataClone) {
                return collect($buyerWise)->flatMap(function ($orderWise) use ($sizes, $totalProductionReportData, $cutProductionReportDataClone) {
                    return $orderWise->flatMap(function ($itemWise) use ($sizes, $totalProductionReportData, $cutProductionReportDataClone) {
                        return $itemWise->flatMap(function ($poWise) use ($sizes, $totalProductionReportData, $cutProductionReportDataClone) {
                            return $poWise->map(function ($colorWise) use ($sizes, $totalProductionReportData, $cutProductionReportDataClone) {
                                return $colorWise->flatMap(function ($value) use ($sizes, $colorWise, $totalProductionReportData, $cutProductionReportDataClone) {
                                    $cuttingFloors = $cutProductionReportDataClone
                                        ->where('purchase_order_id', $value->purchase_order_id)
                                        ->where('garments_item_id', $value->garments_item_id)
                                        ->where('color_id', $value->color_id)
                                        ->unique('cutting_floor_id')
                                        ->implode('cuttingFloor.floor_no', ',');

                                    $totalCutting = collect($totalProductionReportData)
                                        ->where('buyer_id', $value['buyer_id'])
                                        ->where('order_id', $value['order_id'])
                                        ->where('garments_item_id', $value['garments_item_id'])
                                        ->where('purchase_order_id', $value['purchase_order_id'])
                                        ->where('color_id', $value['color_id'])
                                        ->sum('total_cutting');

                                    $orderQty = collect($value->purchaseOrderDetail)
                                        ->where('garments_item_id', $value->garments_item_id)
                                        ->where('color_id', $value->color_id)
                                        ->sum('quantity');

                                    $data = [
                                        "buyer" => $value->buyer->name,
                                        "style_name" => $value->order->style_name,
                                        "po_no" => $value->purchaseOrder->po_no,
                                        "color" => $value->color->name,
                                        "item" => $value->garmentsItem->name,
                                        "order_qty" => $orderQty,
                                        "daily_cutting" => $colorWise->sum('cutting_qty'),
                                        "total_cutting" => $totalCutting,
                                        "cutting_floors" => $cuttingFloors,
                                        "sizes" => [],
                                    ];
                                    foreach ($sizes as $size) {
                                        $data['sizes'][] = [
                                            'size' => $size,
                                            'qty' => $colorWise->where('size_id', $size)->sum('cutting_qty')
                                        ];
                                    }
                                    return $data;
                                });
                            });
                        });
                    });
                });
            });
        return $reportData;
    }
}
