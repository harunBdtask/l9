<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnReceiveReturn;

use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnReceiveReturnStockSummaryService
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
        $receive_return_qty = $yarn['return_qty'];
        $rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $receive_return_amount = round($receive_return_qty * $rate, 4);

        $yarnStockSummary->balance -= $receive_return_qty;
        $yarnStockSummary->balance_amount -= round($receive_return_amount, 4);
        $yarnStockSummary->receive_return_qty += $receive_return_qty;
        $yarnStockSummary->receive_return_amount += $receive_return_amount;
        $yarnStockSummary->save();
    }

    /*
     * =========================/
     * Update Stock Calculation
     * =========================/
     * */
    public function update($yarn, YarnStockSummary $yarnStockSummary)
    {

        $receive_return_qty = request('return_qty') - $yarn->receive_return_qty;
        $rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $receive_return_amount = $receive_return_qty * $rate;

        $yarnStockSummary->balance -= $receive_return_qty;
        $yarnStockSummary->balance_amount -= $receive_return_amount;
        $yarnStockSummary->receive_return_qty += $receive_return_qty;
        $yarnStockSummary->receive_return_amount += $receive_return_amount;
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
        $receive_return_qty = $yarn->return_qty;
        $receive_return_amount = round($receive_return_qty * $rate, 4);

        $yarnStockSummary->balance += $receive_return_qty;
        $yarnStockSummary->balance_amount += $receive_return_amount;
        $yarnStockSummary->receive_return_qty -= $receive_return_qty;
        $yarnStockSummary->receive_return_amount -= $receive_return_amount;

        $yarnStockSummary->save();
    }
}
