<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class ShipmentWiseOrderReportService
{
    public static function reportData(Request $request): array
    {
        $month = $request->get('month') ?? Carbon::now()->format('m');
        $year = $request->get('year') ?? Carbon::now()->format('Y');

        $orders = Order::query()->with('purchaseOrders.poDetails')
            ->whereHas('purchaseOrders', function (Builder $query) use ($month, $year) {
                $query->whereMonth('ex_factory_date', $month)
                    ->whereYear('ex_factory_date', $year);
            })
            ->get();

        $totalValue = 0;
        $orders = collect($orders)->groupBy('buyer_id')
            ->map(function ($order) use ($month, $year, &$totalValue) {
                $qty = collect($order)->sum('pq_qty_sum');
                $value = collect($order)->pluck('purchaseOrders')->map(function ($purchaseOrders) {
                    return $purchaseOrders->map(function ($purchaseOrder) {
                        return $purchaseOrder->poDetails->map(function ($poDetail) use ($purchaseOrder) {
                            return (double)$poDetail->quantity * (double)$purchaseOrder->avg_rate_pc_set;
                        });
                    });
                })->flatten()->sum();
                $totalValue += $value;

                return [
                    'buyer_name' => $order->first()->buyer->name,
                    'total_style' => collect($order)->pluck('style_name')->unique()->count(),
                    'style_name' => collect($order)->pluck('style_name')->unique()->values()->implode(', '),
                    'order_qty' => $qty,
                    'order_value' => $value,
                    'ship_month' => Carbon::make($year . '-' . $month . '-1')->format('F'),
                    'percentage' => 0,
                ];
            });

        return [
            'orders' => $orders,
            'year' => $year,
            'month' => $month,
            'totalValue' => $totalValue,
        ];
    }
}
