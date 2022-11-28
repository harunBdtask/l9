<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PoFile;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\FactoryCapacity;

class CapacityPlanService
{
    public function getCapacityPlan($fromMonth, $toMonth, $currentMonth, $afterThreeMonth)
    {
        return FactoryCapacity::query()
            ->with('garmentsItem')
            ->selectRaw('*,SUM(capacity_pcs) as total_capacity')
            ->when($fromMonth && $toMonth, function ($query) use ($fromMonth, $toMonth) {
                return $query->whereMonth('date', '>=', $fromMonth)
                    ->whereMonth('date', '<=', $toMonth);
            }, function ($query) use ($currentMonth, $afterThreeMonth) {
                return $query->whereMonth('date', '>=', $currentMonth)
                    ->whereMonth('date', '<=', $afterThreeMonth);
            })
            ->whereYear('date', date('Y'))
            ->groupBy('garments_item_id')
            ->get()
            ->map(function ($collection) use ($fromMonth, $toMonth, $currentMonth, $afterThreeMonth) {

                $purchaseOrderDetails = PoColorSizeBreakdown::query()
                    ->with('purchaseOrder')
                    ->whereHas('purchaseOrder', function ($query) use ($fromMonth, $toMonth, $currentMonth, $afterThreeMonth) {
                        $query->when($fromMonth && $toMonth, function ($query) use ($fromMonth, $toMonth) {
                            return $query->whereMonth('ex_factory_date', '>=', $fromMonth)
                                ->whereMonth('ex_factory_date', '<=', $toMonth);
                        }, function ($query) use ($currentMonth, $afterThreeMonth) {
                            return $query->whereMonth('ex_factory_date', '>=', $currentMonth)
                                ->whereMonth('ex_factory_date', '<=', $afterThreeMonth);
                        });
                    })
                    ->where('garments_item_id', $collection->garments_item_id)
                    ->sum('quantity');

                $balance = $collection->total_capacity - $purchaseOrderDetails;

                return [
                    'item' => $collection->garmentsItem->name,
                    'total_capacity' => $collection->total_capacity,
                    'total_po_received' => $purchaseOrderDetails ?? 0,
                    'balance' => $balance
                ];
            });
    }

    public function months(): array
    {
        $months = [];
        foreach (range(1, 12) as $month) {
            $months[$month] = Carbon::create()->month($month)->format('M');
        }
        return $months;
    }
}
