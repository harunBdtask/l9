<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;

class YarnStockSummaryCalculator
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
    public function create($yarn)
    {
        $receive_qty = $yarn['receive_qty'];
        $receive_amount = round($yarn['receive_qty'] * $yarn['rate'], 4);

        $meta = [
            'yarn_count' => optional($yarn->yarn_count)->yarn_count,
            'yarn_composition' => optional($yarn->composition)->yarn_composition,
            'yarn_type' => optional($yarn->type)->name,
            'yarn_brand' => $yarn['yarn_brand'],
            'yarn_lot' => $yarn['yarn_lot'],
            'yarn_color' => $yarn['yarn_color'],
        ];

        YarnStockSummary::query()->create([
            'yarn_lot' => $yarn['yarn_lot'],
            'store_id' => $yarn['store_id'],
            'uom_id' => $yarn['uom_id'],
            'yarn_color' => $yarn['yarn_color'],
            'yarn_brand' => $yarn['yarn_brand'],
            'yarn_type_id' => $yarn['yarn_type_id'],
            'yarn_count_id' => $yarn['yarn_count_id'],
            'yarn_composition_id' => $yarn['yarn_composition_id'],
            'balance' => $receive_qty,
            'balance_amount' => $receive_amount,
            'receive_qty' => $receive_qty,
            'receive_amount' => $receive_amount,
            'meta' => $meta
        ]);
    }

    public function createSameItem($yarn, YarnStockSummary $yarnStockSummary)
    {
        $receive_qty = $yarn['receive_qty'];
        $receive_amount = round($yarn['receive_qty'] * $yarn['rate'], 4);

        $yarnStockSummary->balance += $receive_qty;
        $yarnStockSummary->balance_amount += round($receive_amount, 4);
        $yarnStockSummary->receive_qty += $receive_qty;
        $yarnStockSummary->receive_amount += $receive_amount;

        $yarnStockSummary->save();
    }

    /*
     * =========================/
     * Update Stock Calculation
     * =========================/
     * */
    public function update($yarn, YarnStockSummary $yarnStockSummary)
    {
        $receive_qty = request('receive_qty') - $yarn->receive_qty;
        $receive_amount = round(request('receive_qty') * request('rate'), 4) - round($yarn->receive_qty * $yarn->rate, 4);

        $yarnStockSummary->balance += $receive_qty;
        $yarnStockSummary->balance_amount += $receive_amount;
        $yarnStockSummary->receive_qty += $receive_qty;
        $yarnStockSummary->receive_amount += $receive_amount;

        $yarnStockSummary->save();
    }


    /*
     * =========================/
     * Delete Stock Calculation
     * =========================/
     * */
    public function delete($yarn, YarnStockSummary $yarnStockSummary)
    {
        $receive_qty = $yarn->receive_qty;
        $receive_amount = round($yarn->receive_qty * $yarn->rate, 4);

        $yarnStockSummary->balance -= $receive_qty;
        $yarnStockSummary->balance_amount -= $receive_amount;
        $yarnStockSummary->receive_qty -= $receive_qty;
        $yarnStockSummary->receive_amount -= $receive_amount;

        $yarnStockSummary->save();
    }

}
