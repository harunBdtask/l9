<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;

class YarnDateWiseSummaryCalculator
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
            ->whereDate('date', $yarn->yarnReceive['receive_date'])
            ->first();
    }

    /*
     * =========================/
     * Creation Stock Calculation
     * =========================/
     * */
    public function create($yarn, $date)
    {
        $receive_qty = $yarn['receive_qty'];
        $receive_amount = round($yarn['receive_qty'] * $yarn['rate'], 4);

        YarnDateWiseStockSummary::query()->create([
            'factory_id' => $yarn->yarnReceive->factory_id,
            'yarn_lot' => $yarn['yarn_lot'],
            'store_id' => $yarn['store_id'],
            'uom_id' => $yarn['uom_id'],
            'yarn_color' => $yarn['yarn_color'],
            'yarn_brand' => $yarn['yarn_brand'],
            'yarn_type_id' => $yarn['yarn_type_id'],
            'yarn_count_id' => $yarn['yarn_count_id'],
            'yarn_composition_id' => $yarn['yarn_composition_id'],
            'receive_qty' => $receive_qty,
            'date' => $date,
            'rate' => (new YarnStockSummaryService())->getYarnRate($yarn),
        ]);
    }

    public function createSameItem($yarn, YarnDateWiseStockSummary $existingDateWiseStockSummary)
    {
        $existingDateWiseStockSummary->receive_qty += $yarn['receive_qty'];
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
        $newReceiveQty = request('receive_qty') - $yarn->receive_qty;

        $existingDateWiseStockSummary->receive_qty += $newReceiveQty;
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
        $existingDateWiseStockSummary->receive_qty -= $yarn->receive_qty;
        $existingDateWiseStockSummary->rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $existingDateWiseStockSummary->save();

        if ($existingDateWiseStockSummary->receive_qty == 0 &&
            $existingDateWiseStockSummary->receive_return_qty == 0 &&
            $existingDateWiseStockSummary->issue_qty == 0 &&
            $existingDateWiseStockSummary->issue_return_qty == 0
        ) {
            $existingDateWiseStockSummary->delete();
        }
    }
}
