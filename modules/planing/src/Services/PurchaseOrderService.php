<?php

namespace SkylarkSoft\GoRMG\Planing\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Planing\Models\ContainerProfile\ContainerSummaries;

class PurchaseOrderService
{
    public static function purchaseOrders(Request $request)
    {
        $search = $request->query('search');

        $containerFillUpPo = ContainerSummaries::query()
            ->where('ex_factory_date', '>=', date('Y-m-d'))
            ->get()->pluck('po_list')
            ->flatten(1)
            ->pluck('id')
            ->unique();

        return PurchaseOrder::query()
            ->with('order.styleEntry')
            ->whereNotIn('id', $containerFillUpPo)
            ->where('ex_factory_date', '>=', date('Y-m-d'))
            ->when($search, function (Builder $query) use ($search) {
                $query->where('po_no', 'LIKE', "%{$search}%");
            })
            ->get()->map(function ($collection) {
                $pcsPerCarton = $collection->order->styleEntry->pcs_per_carton ?? 0;
                $cbmPerCarton = $collection->order->styleEntry->cbm_per_carton ?? 0;

                if ($pcsPerCarton == 0 || $cbmPerCarton == 0) {
                    $cbm = 0;
                } else {
                    $cbm = $collection->po_quantity / $pcsPerCarton;
                    $cbm = $cbm * $cbmPerCarton;
                }

                return [
                    'id' => $collection->id,
                    'title' => $collection->po_no,
                    'ex_factory_date' => Carbon::make($collection->ex_factory_date)->format('Y-m-d'),
                    'cbm' => $cbm,
                ];
            });
    }
}
