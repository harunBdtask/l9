<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Services;

use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateChallanWiseEmbrReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateChallanWisePrintReport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class PrintEmbrReportService
{
    public static function challanWiseEmbr($color_id, $order_id): array
    {
        $orders = Order::query()->where('id', $order_id)->pluck('style_name', 'id');
        $order = $orders[$order_id];
        $reports = ManualDateChallanWiseEmbrReport::query()->where('order_id', $order_id);
        if ($color_id) {
            $reports = $reports->where('color_id', $color_id);
        }
        $reports = $reports->get()->groupBy('color_id');
        return [$order, $orders, $reports];
    }

    public static function challanWisePrint($color_id, $order_id): array
    {
        $orders = Order::query()->where('id', $order_id)->pluck('style_name', 'id');
        $order = $orders[$order_id];
        $reports = ManualDateChallanWisePrintReport::query()->where('order_id', $order_id);
        if ($color_id) {
            $reports = $reports->where('color_id', $color_id);
        }
        $reports = $reports->get()->groupBy('color_id');
        return [$order, $orders, $reports];
    }
}
