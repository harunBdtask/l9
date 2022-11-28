<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnIssue;

use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnIssueStockSummaryService
{
    public function summary($yarn)
    {
        return YarnStockSummary::query()
            ->where([
                'uom_id' => $yarn['uom_id'],
                'yarn_lot' => $yarn['yarn_lot'],
                'store_id' => $yarn['store_id'],
                'yarn_color' => $yarn['yarn_color'],
                'yarn_brand' => $yarn['yarn_brand'],
                'yarn_type_id' => $yarn['yarn_type_id'],
                'yarn_count_id' => $yarn['yarn_count_id'],
                'yarn_composition_id' => $yarn['yarn_composition_id']
            ])
            ->first();
    }

    /*
     * =========================/
     * Creation Stock Calculation
     * =========================/
     * */
    public function createSameItem($yarn, YarnStockSummary $yarnStockSummary)
    {
        $issue_qty = $yarn['issue_qty'];
        $rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $issue_amount = round($yarn['issue_qty'] * $rate, 4);

        $yarnStockSummary->balance -= $issue_qty;
        $yarnStockSummary->balance_amount -= round($issue_amount, 4);
        $yarnStockSummary->issue_qty += $issue_qty;
        $yarnStockSummary->issue_amount += $issue_amount;

        $yarnStockSummary->save();
    }

    /*
     * =========================/
     * Update Stock Calculation
     * =========================/
     * */
    public function update($yarn, YarnStockSummary $yarnStockSummary)
    {

        $issue_qty = request('issue_qty') - $yarn->issue_qty;
        $rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $issue_amount = $issue_qty * $rate;

        $yarnStockSummary->balance -= $issue_qty;
        $yarnStockSummary->balance_amount -= $issue_amount;
        $yarnStockSummary->issue_qty += $issue_qty;
        $yarnStockSummary->issue_amount += $issue_amount;
        $yarnStockSummary->save();
    }

    /*
     * =========================/
     * Delete Stock Calculation
     * =========================/
     * */
    public function delete($yarn, YarnStockSummary $yarnStockSummary)
    {
        $rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $issue_qty = $yarn->issue_qty;
        $issue_amount = round($issue_qty * $rate, 4);

        $yarnStockSummary->balance += $issue_qty;
        $yarnStockSummary->balance_amount += $issue_amount;
        $yarnStockSummary->issue_qty -= $issue_qty;
        $yarnStockSummary->issue_amount -= $issue_amount;

        $yarnStockSummary->save();
    }
}
