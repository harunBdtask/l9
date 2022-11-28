<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services;


use Carbon\Carbon;
use SkylarkSoft\GoRMG\Merchandising\Models\ASIConsumption\ASIConsumption;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Services\Month\MonthService;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\TestFixture\func;

class SeasonWiseOrderOverviewReportService
{
    public static function reportData($year, $season)
    {
        $orders = Order::query()->whereHas('season', function ($query) use ($season) {
            return $query->where('season_name', $season);
        })->with(['purchaseOrders' => function ($query) use ($year) {
            return $query->whereYear('ex_factory_date', $year);
        }])->get();

        $purchaseOrders = count($orders) > 0  ? collect($orders)->pluck('purchaseOrders')->flatten()->map(function ($item) {
            $qty = $item->po_quantity ?? 0;
            $rate = $item->avg_rate_pc_set ?? 0;
            return [
                'month' => isset($item->ex_factory_date) ? Carbon::parse($item->ex_factory_date)->format('m') : null,
                'po_qty' => $qty,
                'avg_rate_pc_set' => $rate,
                'amount' => $qty * $rate
            ];
        }) : [];

        return count($purchaseOrders) > 0 ? self::groupByMonth($purchaseOrders) : [];

    }

    public static function groupByMonth($purchaseOrders)
    {
        return collect($purchaseOrders)->groupBy('month')->map(function ($item, $month) {
            return [
                'month' => $month,
                'month_name' => MonthService::getMonth($month),
                'total_qty' => collect($item)->sum('po_qty'),
                'total_amount' => collect($item)->sum('amount'),
            ];
        })->sortBy('month')->values();
    }

}
