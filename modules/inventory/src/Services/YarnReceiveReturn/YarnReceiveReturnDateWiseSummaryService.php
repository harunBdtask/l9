<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnReceiveReturn;

use App\Exceptions\DivisionByZeroException;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnReceiveReturnDateWiseSummaryService
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
            ->whereDate('date', $yarn->receiveReturn['return_date'])
            ->first();
    }

    /*
     * =========================/
     * Creation Stock Calculation
     * =========================/
     * */
    public function create($yarn)
    {
        YarnDateWiseStockSummary::query()->create([
            'factory_id' => $yarn->receiveReturn->factory_id,
            'yarn_lot' => $yarn['yarn_lot'],
            'store_id' => $yarn['store_id'],
            'uom_id' => $yarn['uom_id'],
            'yarn_color' => $yarn['yarn_color'],
            'yarn_brand' => $yarn['yarn_brand'],
            'yarn_type_id' => $yarn['yarn_type_id'],
            'yarn_count_id' => $yarn['yarn_count_id'],
            'yarn_composition_id' => $yarn['yarn_composition_id'],
            'receive_return_qty' => $yarn['return_qty'],
            'date' => $yarn->receiveReturn->return_date,
            'rate' => (new YarnStockSummaryService())->getYarnRate($yarn),
        ]);
    }

    public function createSameItem($yarn, YarnDateWiseStockSummary $yarnDateWiseStockSummary)
    {
        $yarnDateWiseStockSummary->receive_return_qty += $yarn['return_qty'];
        $yarnDateWiseStockSummary->rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $yarnDateWiseStockSummary->save();
    }

    /*
     * =========================/
     * Update Stock Calculation
     * =========================/
     * */
    public function update($yarn, $yarnDateWiseStockSummary)
    {
        $newReceiveQty = request('return_qty') - $yarn->return_qty;

        $yarnDateWiseStockSummary->receive_return_qty += $newReceiveQty;
        $yarnDateWiseStockSummary->rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $yarnDateWiseStockSummary->save();
    }

    /*
     * =========================/
     * Delete Stock Calculation
     * =========================/
     * */
    public function delete($yarn, $yarnDateWiseStockSummary)
    {
        $yarnDateWiseStockSummary->receive_return_qty -= $yarn->return_qty;
        $yarnDateWiseStockSummary->rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $yarnDateWiseStockSummary->save();
    }
}
