<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnIssueReturn;

use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnIssueReturnDateWiseStockSummary
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
            ->whereDate('date', $yarn->issueReturn['return_date'])
            ->first();
    }

    /*
     * =========================/
     * Creation Stock Calculation
     * =========================/
     * */
    public function create($yarn)
    {
        $return_qty = $yarn['return_qty'];

        YarnDateWiseStockSummary::query()->create([
            'factory_id' => $yarn->issueReturn['factory_id'],
            'yarn_lot' => $yarn['yarn_lot'],
            'store_id' => $yarn['store_id'],
            'uom_id' => $yarn['uom_id'],
            'yarn_color' => $yarn['yarn_color'],
            'yarn_brand' => $yarn['yarn_brand'],
            'yarn_type_id' => $yarn['yarn_type_id'],
            'yarn_count_id' => $yarn['yarn_count_id'],
            'yarn_composition_id' => $yarn['yarn_composition_id'],
            'issue_return_qty' => $return_qty,
            'date' => $yarn->issueReturn['return_date'],
            'rate' => (new YarnStockSummaryService())->getYarnRate($yarn),
        ]);
    }

    public function createSameItem($yarn, YarnDateWiseStockSummary $dateWiseYarnStockSummary)
    {
        $dateWiseYarnStockSummary->issue_return_qty += $yarn['return_qty'];
        $dateWiseYarnStockSummary->rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $dateWiseYarnStockSummary->save();
    }

    /*
     * =========================/
     * Delete Stock Calculation
     * =========================/
     * */
    public function delete($yarn, $dateWiseYarnStockSummary)
    {
        $dateWiseYarnStockSummary->issue_return_qty -= $yarn->return_qty;
        $dateWiseYarnStockSummary->rate = (new YarnStockSummaryService())->getYarnRate($yarn);
        $dateWiseYarnStockSummary->save();

        if ($dateWiseYarnStockSummary->receive_qty == 0 &&
            $dateWiseYarnStockSummary->receive_return_qty == 0 &&
            $dateWiseYarnStockSummary->issue_qty == 0 &&
            $dateWiseYarnStockSummary->issue_return_qty == 0
        ) {
            $dateWiseYarnStockSummary->delete();
        }
    }
}
