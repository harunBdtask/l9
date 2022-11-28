<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class MonthWisePOReportService
{
    public static function reportData($factoryId, $buyerId, $month, $year)
    {
        $orders = PurchaseOrder::query()
            ->where('factory_id', $factoryId)
            ->when($buyerId, function ($query) use ($buyerId) {
                return $query->where('buyer_id', $buyerId);
            })
            ->whereYear('ex_factory_date', $year)
            ->whereMonth('ex_factory_date', $month)
            ->with(['buyer:id,name', 'order.season'])
            ->get();
//        return $orders;
        return count($orders) > 0 ? self::formatData($orders) : [];

    }

    public static function reportDataPdf(Request $request)
    {

        $factoryId = $request->get('factoryId') ?? factoryId();
        $buyerId = $request->get('buyerId') ?? null;
        $month = $request->get('month') ?? Carbon::now()->format('m');
        $year = $request->get('year') ?? Carbon::now()->format('Y');

        $orders = PurchaseOrder::query()
            ->where('factory_id', $factoryId)
            ->when($buyerId, function ($query) use ($buyerId) {
                return $query->where('buyer_id', $buyerId);
            })
            ->whereYear('ex_factory_date', $year)
            ->whereMonth('ex_factory_date', $month)
            ->with(['buyer:id,name', 'order.season'])
            ->get();
//        return $orders;
        return count($orders) > 0 ? self::formatData($orders) : [];

    }

    public static function reportDataExcel(Request $request)
    {

        $factoryId = $request->get('factoryId') ?? factoryId();
        $buyerId = $request->get('buyerId') ?? null;
        $month = $request->get('month') ?? Carbon::now()->format('m');
        $year = $request->get('year') ?? Carbon::now()->format('Y');

        $orders = PurchaseOrder::query()
            ->where('factory_id', $factoryId)
            ->when($buyerId, function ($query) use ($buyerId) {
                return $query->where('buyer_id', $buyerId);
            })
            ->whereYear('ex_factory_date', $year)
            ->whereMonth('ex_factory_date', $month)
            ->with(['buyer:id,name', 'order.season'])
            ->get();
//        return $orders;
        return count($orders) > 0 ? self::formatData($orders) : [];

    }

    public static function formatData($orders)
    {
        return collect($orders)->map(function ($item) {
            $qty = $item->po_quantity ?? 0;
            $rate = $item->avg_rate_pc_set ?? 0;
            return [
                'style' => $item->order->style_name ?? '',
                'buyer' => $item->buyer->name ?? '',
                'season' => $item->order->season->season_name ?? '',
                'po' => $item->po_no ?? '',
                'po_qty' => $qty,
                'value' => $qty * $rate,
                'ex_factory_date' => $item->ex_factory_date
            ];
        });
    }
}
