<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Inventory\Exceptions\DateNotAvailableException;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;

class YarnDateWiseSummaryService
{
    /**
     * @throws DateNotAvailableException
     */
    public function getDateWiseSummary($yarn, $date)
    {
        if (! $date) {
            throw new DateNotAvailableException('Date is not available for date wise summary!');
        }

        return YarnDateWiseStockSummary::where('yarn_count_id', $yarn['yarn_count_id'])
            ->where('yarn_composition_id', $yarn['yarn_composition_id'])
            ->where('yarn_type_id', $yarn['yarn_type_id'])
            ->where('yarn_lot', $yarn['yarn_lot'])
            ->where('uom_id', $yarn['uom_id'])
            ->where('yarn_color', $yarn['yarn_color'])
            ->where('store_id', $yarn['store_id'])
            ->where('date', $date)
            ->first();
    }
}
