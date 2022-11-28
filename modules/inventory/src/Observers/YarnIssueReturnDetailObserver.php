<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use App\Exceptions\DivisionByZeroException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Exceptions\DateNotAvailableException;
use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsDateWiseStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnDateWiseSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnIssueReturnDetailObserver
{

    private $stockSummary;

    public function __construct()
    {
        $this->stockSummary = new YarnStockSummaryService;
    }

    /**
     * @throws SummaryNotFoundException
     * @throws DivisionByZeroException
     */
    public function saved(YarnIssueReturnDetail $yarn)
    {
        if ( !$yarn->wasRecentlyCreated ) {
            return;
        }

        try {
            $summary = $this->stockSummary->summary($yarn);
            $rate = $this->stockSummary->yarnRate($summary);
            $returnQty = $yarn->return_qty;
            $balance = $summary->balance - $returnQty;

            $summary->update([
                'issue_return_qty' => $summary->issue_return_qty + $returnQty,
                'balance'          => $balance,
                'balance_amount'   => $balance * $rate
            ]);


            /**
             * Date Wise Stock Summary
             */

            $issueReturnDate = $yarn->issueReturn->return_date;

            $dateWiseStockSummary = (new YarnDateWiseSummaryService)
                ->getDateWiseSummary($yarn, $issueReturnDate);

            if ( !$dateWiseStockSummary ) {
                (new YarnDateWiseStockSummary([
                    'date'                => $issueReturnDate,
                    'store_id'            => $yarn->store_id,
                    'yarn_count_id'       => $yarn->yarn_count_id,
                    'yarn_composition_id' => $yarn->yarn_composition_id,
                    'yarn_type_id'        => $yarn->yarn_type_id,
                    'yarn_color'          => $yarn->yarn_color,
                    'yarn_lot'            => $yarn->yarn_lot,
                    'uom_id'              => $yarn->uom_id,
                    'issue_return_qty'    => $yarn->return_qty,
                    'rate'                => $rate,
                ]))->save();

                return;
            }


            $dateWiseStockSummary->update([
                'issue_return_qty' => $dateWiseStockSummary->issue_return_qty + $yarn->return_qty,
                'rate'             => $rate
            ]);


        } catch (ModelNotFoundException $e) {
            throw new SummaryNotFoundException('No Receive Available for this Item!');
        } catch (DateNotAvailableException $e) {
        }
    }

    /**
     * @throws SummaryNotFoundException
     * @throws DivisionByZeroException
     */
    public function deleted(YarnIssueReturnDetail $yarn)
    {
        try {

            $summary = $this->stockSummary->summary($yarn);
            $rate = $this->stockSummary->yarnRate($yarn);

            $summary->balance += $yarn->return_qty;
            $summary->balance_amount += ($rate * $yarn->return_qty);
            $summary->issue_return_qty -= $yarn->return_qty;
            $summary->save();

            // TODO: Date wise stock summary

        } catch (ModelNotFoundException $e) {
            throw new SummaryNotFoundException('No Summary Available for this Item!');
        }
    }
}
