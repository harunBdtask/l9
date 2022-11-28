<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Services\Reports;

use SkylarkSoft\GoRMG\GeneralStore\Models\FabricDateWiseStockReportSummery;

class FabricDateWiseStockSummeryReportService
{
    public static function index($start_date, $end_date, $poId, $type)
    {
        $data = FabricDateWiseStockReportSummery::with('style', 'fabrication', 'color', 'uomDetails')
            ->whereDate('production_date', '>=', $start_date)
            ->whereDate('production_date', '<=', $end_date)
            ->where('po_id', $poId)
            ->where(function ($query) {
                return $query->where('receive_qty', '>', 0)
                    ->orWhere('deliver_qty', '>', 0);
            })->get();

        return $data;
    }
}
