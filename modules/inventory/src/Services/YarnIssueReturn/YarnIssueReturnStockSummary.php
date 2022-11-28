<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnIssueReturn;


use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;

class YarnIssueReturnStockSummary
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

    public function createSameItem($yarn, YarnStockSummary $yarnStockSummary)
    {
        $issue_return_qty = $yarn['return_qty'];
        $issue_return_amount = round($issue_return_qty * $yarn['rate'], 4);

        $yarnStockSummary->balance += $issue_return_qty;
        $yarnStockSummary->balance_amount += round($issue_return_amount, 4);
        $yarnStockSummary->issue_return_qty += $issue_return_qty;
        $yarnStockSummary->issue_return_amount += $issue_return_amount;

        $yarnStockSummary->save();
    }


    /*
     * =========================/
     * Delete Issue Return Stock Calculation
     * =========================/
     * */
    public function delete($yarn, YarnStockSummary $yarnStockSummary)
    {
        $issue_return_qty = $yarn->return_qty;
        $issue_return_amount = round($yarn->issue_return_qty * $yarn->rate, 4);

        $yarnStockSummary->balance -= $issue_return_qty;
        $yarnStockSummary->balance_amount -= $issue_return_amount;
        $yarnStockSummary->issue_return_qty -= $issue_return_qty;
        $yarnStockSummary->issue_return_amount -= $issue_return_amount;
        $yarnStockSummary->save();
    }

}
