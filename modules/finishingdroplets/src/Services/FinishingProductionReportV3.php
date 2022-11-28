<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Services;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\Poly;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail;

class FinishingProductionReportV3
{
    protected $fromDate, $toDate, $buyer, $floorId, $ironFinishData, $receivedData;

    public function __construct($fromDate, $toDate, $buyer, $floorId)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->buyer = $buyer;
        $this->floorId = $floorId;
    }

    public function generateReport($mode, $limit = 50, $offset = 0): array
    {
        $dateRangeBetweenPoly = Poly::query()
            ->with(['buyer', 'purchaseOrder.country', 'order', 'color', 'finishingFloor'])
            ->select(['id', 'purchase_order_id', 'color_id', 'buyer_id', 'order_id', 'production_date'])
            ->whereBetween('production_date', [$this->fromDate, $this->toDate])
            ->when($this->buyer, function ($q) {
                return $q->where('buyer_id', $this->buyer);
            })
            ->when($this->floorId, function ($q) {
                return $q->where('finishing_floor_id', $this->floorId);
            })
            ->groupBy(['production_date', 'order_id', 'purchase_order_id', 'color_id'])
            ->when($mode == 'BLADE', function ($q) use ($limit, $offset) {
                $q->limit($limit)->offset($offset);
            })
            ->get();

        $allPos = $dateRangeBetweenPoly
            ->pluck('purchase_order_id')
            ->unique()
            ->values();
        $allColors = $dateRangeBetweenPoly
            ->pluck('color_id')
            ->unique()
            ->values();

        $this->ironFinishData = $this->ironFinishDataGenerator($allPos, $allColors);
        $this->receivedData = $this->receivedDataGenerator($allPos, $allColors);
        return $this->format($dateRangeBetweenPoly, $allPos, $allColors);
    }

    public function ironFinishDataGenerator($allPos, $allColors)
    {
        return Poly::query()
            ->with(['finishingFloor', 'buyer', 'order', 'purchaseOrder', 'color'])
            ->selectRaw(DB::raw("*,SUM(iron_qty) AS total_iron_qty,SUM(packing_qty) AS total_packing_qty"))
            ->when($this->buyer, function ($q) {
                return $q->where('buyer_id', $this->buyer);
            })
            ->whereIn('purchase_order_id', $allPos)
            ->whereIn('color_id', $allColors)
            ->groupBy(['production_date', 'order_id', 'purchase_order_id', 'color_id'])
            ->get();
    }

    public function receivedDataGenerator($allPos, $allColors)
    {
        return FinishingProductionReport::query()
            ->with('floorWithoutGlobalScopes')
            ->selectRaw(DB::raw("*,SUM(sewing_output) AS total_sewing_production"))
            ->when($this->buyer, function ($q) {
                return $q->where('buyer_id', $this->buyer);
            })
            ->whereIn('purchase_order_id', $allPos)
            ->whereIn('color_id', $allColors)
            ->where('sewing_output', '>', 0)
            ->groupBy(['production_date', 'order_id', 'purchase_order_id', 'color_id'])
            ->get();
    }

    public function format($dateRangeBetweenPoly, $allPos, $allColors): array
    {
        $allOrderQty = PurchaseOrderDetail::query()
            ->whereIn('purchase_order_id', $allPos)
            ->whereIn('color_id', $allColors)
            ->select('color_id', 'purchase_order_id', 'quantity')
            ->get();

        return collect($dateRangeBetweenPoly)
            ->sortBy('production_date')
            ->map(function ($collection) use ($allOrderQty) {

                $orderQty = $allOrderQty
                    ->where('purchase_order_id', $collection->purchase_order_id)
                    ->where('color_id', $collection->color_id)->sum('quantity');

                $orderQtyAddingOnePercent = $orderQty + ($orderQty * 0.1);

                $dailyIronFinishData = collect($this->ironFinishData)
                    ->where('color_id', $collection->color_id)
                    ->where('purchase_order_id', $collection->purchase_order_id)
                    ->where('production_date', date('Y-m-d'));
                $prevIronFinishData = collect($this->ironFinishData)
                    ->where('color_id', $collection->color_id)
                    ->where('purchase_order_id', $collection->purchase_order_id)
                    ->where('production_date', '!=', date('Y-m-d'));

                $dailyIron = $dailyIronFinishData->sum('total_iron_qty');
                $prevIron = $prevIronFinishData->sum('total_iron_qty');
                $dailyPacking = $dailyIronFinishData->sum('total_packing_qty');
                $prevPacking = $prevIronFinishData->sum('total_packing_qty');

                $dailyReceived = collect($this->receivedData)
                    ->where('production_date', date('Y-m-d'))
                    ->where('color_id', $collection->color_id)
                    ->where('purchase_order_id', $collection->purchase_order_id)
                    ->sum('sewing_output');
                $prevReceived = collect($this->receivedData)
                    ->where('production_date', '!=', date('Y-m-d'))
                    ->where('color_id', $collection->color_id)
                    ->where('purchase_order_id', $collection->purchase_order_id)
                    ->sum('sewing_output');

                $sewingFloors = collect($this->receivedData)
                    ->where('color_id', $collection->color_id)
                    ->where('purchase_order_id', $collection->purchase_order_id)
                    ->pluck('floorWithoutGlobalScopes.floor_no')->unique()->implode(', ');

                return [
                    'production_date' => $collection->production_date ? Carbon::make($collection->production_date)
                        ->format('d-m-Y') : null,
                    'buyer' => $collection->buyer->name,
                    'style' => $collection->order->style_name ?? null,
                    'po_no' => $collection->purchaseOrder->po_no ?? null,
                    'country' => $collection->purchaseOrder->country->name ?? null,
                    'color' => $collection->color->name ?? null,
                    'order_qty' => $orderQty,
                    'order_qty_one_percentage' => $orderQtyAddingOnePercent,
                    'daily_received' => $dailyReceived,
                    'pre_received' => $prevReceived,
                    'total_received' => $prevReceived + $dailyReceived,
                    'daily_iron' => $dailyIron,
                    'pre_iron' => $prevIron,
                    'total_iron' => $dailyIron + $prevIron,
                    'daily_finish' => $dailyPacking,
                    'pre_finish' => $prevPacking,
                    'total_finish' => $dailyPacking + $prevPacking,
                    'balance_qty' => ($dailyPacking + $prevPacking) - $orderQty,
                    'finish_floor' => $collection->finishingFloor->name ?? null,
                    'sewing_floor' => $sewingFloors,
                    'remarks' => $collection->first()['remarks'] ?? null,
                ];
            })->toArray();
    }
}
