<?php


namespace SkylarkSoft\GoRMG\Inventory\Services;


use SkylarkSoft\GoRMG\Inventory\Exceptions\DateNotAvailableException;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsDateWiseStockSummary;

class TrimsDateWiseStockSummaryService
{
    /**
     * @throws \Exception
     */
    public function getDateWiseSummary($detail, $date)
    {

        if ( !$date ) {
            throw new DateNotAvailableException('Date is not available for date wise summary');
        }

        return TrimsDateWiseStockSummary::where([
            'style_name' => $detail->style_name,
            'item_id'    => $detail->item_id,
            'uom_id'     => $detail->uom_id
        ])->whereDate('date', $date)->first();
    }
}