<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnIssue;

use App\Exceptions\DivisionByZeroException;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnIssueDateWiseSummaryService
{
    public function summary($yarn)
    {
        return YarnDateWiseStockSummary::query()
            ->where([
                'uom_id' => $yarn['uom_id'],
                'yarn_lot' => $yarn['yarn_lot'],
                'store_id' => $yarn['store_id'],
                'yarn_color' => $yarn['yarn_color'],
                'yarn_brand' => $yarn['yarn_brand'],
                'yarn_type_id' => $yarn['yarn_type_id'],
                'yarn_count_id' => $yarn['yarn_count_id'],
                'yarn_composition_id' => $yarn['yarn_composition_id'],
            ])
            ->whereDate('date', $yarn->issue['issue_date'])
            ->first();
    }

    /*
     * =========================/
     * Creation Stock Calculation
     * =========================/
     * */
    public function create($yarn, $date)
    {
        YarnDateWiseStockSummary::query()->create([
            'factory_id' => $yarn->issue->factory_id,
            'yarn_lot' => $yarn['yarn_lot'],
            'store_id' => $yarn['store_id'],
            'uom_id' => $yarn['uom_id'],
            'yarn_color' => $yarn['yarn_color'],
            'yarn_brand' => $yarn['yarn_brand'],
            'yarn_type_id' => $yarn['yarn_type_id'],
            'yarn_count_id' => $yarn['yarn_count_id'],
            'yarn_composition_id' => $yarn['yarn_composition_id'],
            'issue_qty' => $yarn['issue_qty'],
            'date' => $date,
            'rate' => (new YarnStockSummaryService())->getYarnRate($yarn),
        ]);
    }

    public function createSameItem($yarn, YarnDateWiseStockSummary $existingDateWiseStockSummary)
    {
        $existingDateWiseStockSummary->issue_qty += $yarn['issue_qty'];
        $existingDateWiseStockSummary->rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $existingDateWiseStockSummary->save();
    }

    /*
     * =========================/
     * Update Stock Calculation
     * =========================/
     * */
    public function update($yarn, $existingDateWiseStockSummary)
    {
        $newReceiveQty = request('issue_qty') - $yarn->issue_qty;

        $existingDateWiseStockSummary->issue_qty += $newReceiveQty;
        $existingDateWiseStockSummary->rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $existingDateWiseStockSummary->save();
    }

    /*
     * =========================/
     * Delete Stock Calculation
     * =========================/
     * */
    public function delete($yarn, $existingDateWiseStockSummary)
    {
        $existingDateWiseStockSummary->issue_qty -= $yarn->issue_qty;
        $existingDateWiseStockSummary->rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $existingDateWiseStockSummary->save();
    }
}
