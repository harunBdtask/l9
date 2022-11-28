<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Services;

use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDailyProductionReport;

class RejectionService
{
    public static function yearlyRejection($year)
    {
        return ManualDailyProductionReport::query()
            ->selectRaw('MONTH(production_date) as month, order_id,
                SUM(cutting_qty) as cutting_qty,
                SUM(input_qty) as input_qty,
                SUM(sewing_output_qty) as sewing_output_qty,
                SUM(cutting_rejection_qty) as cutting_rejection_qty,
                SUM(print_rejection_qty) as print_rejection_qty,
                SUM(embroidery_rejection_qty) as embroidery_rejection_qty,
                SUM(sewing_rejection_qty) as sewing_rejection_qty
                ')
            ->whereYear('production_date', $year)
            ->groupBy('month', 'order_id')
            ->get();
    }
}
