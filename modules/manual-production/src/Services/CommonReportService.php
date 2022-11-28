<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Services;

use SkylarkSoft\GoRMG\ManualProduction\Models\ManualSewingInputProduction;

class CommonReportService
{
    public static function challanWiseStyleInput($order_id, $color_id)
    {
        return ManualSewingInputProduction::query()
            ->selectRaw('buyer_id, order_id, color_id, challan_no, floor_id, line_id, production_date,SUM(production_qty) AS production_qty')
            ->where('order_id', $order_id)
            ->when($color_id, function ($q, $color_id) {
                $q->where('color_id', $color_id);
            })->groupBy('buyer_id', 'order_id', 'color_id', 'challan_no', 'floor_id', 'line_id', 'production_date')->get();
    }
}
