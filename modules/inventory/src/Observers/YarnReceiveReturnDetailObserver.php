<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use App\Exceptions\DivisionByZeroException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Exceptions\DateNotAvailableException;
use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnDateWiseSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnReceiveReturnDetailObserver
{

    /**
     * @var YarnStockSummaryService
     */
    private $stockSummaryService;

    public function __construct()
    {
        $this->stockSummaryService = new YarnStockSummaryService();
    }

    /**
     * @throws SummaryNotFoundException
     * @throws DivisionByZeroException
     */
    public function saved(YarnReceiveReturnDetail $yarn)
    {
        try {
            $summary = $this->stockSummaryService->summary($yarn);
            $rate = $this->stockSummaryService->yarnRate($summary);
            $balance = $summary->balance - $yarn->return_qty;

            $summary->update([
                'receive_return_qty' => $summary->receive_return_qty + $yarn->return_qty,
                'balance' => $balance,
                'balance_amount' => $balance * $rate
            ]);

            $date = $yarn->receiveReturn->return_date;
            $dateWiseSummary = (new YarnDateWiseSummaryService)->getDateWiseSummary($yarn, $date);

            if (!$dateWiseSummary) {
                (new YarnDateWiseStockSummary([
                    'date' => $date,
                    'store_id' => $yarn->store_id,
                    'yarn_count_id' => $yarn->yarn_count_id,
                    'yarn_composition_id' => $yarn->yarn_composition_id,
                    'yarn_type_id' => $yarn->yarn_type_id,
                    'yarn_color' => $yarn->yarn_color,
                    'yarn_lot' => $yarn->yarn_lot,
                    'uom_id' => $yarn->uom_id,
                    'receive_return_qty' => $yarn->return_qty,
                    'rate' => (new YarnStockSummaryService())->getYarnRate($yarn),
                ]))->save();

                return;
            }

            $totalReturnQty = $dateWiseSummary->receive_return_qty + $yarn->return_qty;

            $dateWiseSummary->update([
                'receive_return_qty' => $totalReturnQty,
                'rate' => (new YarnStockSummaryService())->getYarnRate($yarn)
            ]);

        } catch (ModelNotFoundException $e) {
//            dd($e->getMessage());
            throw new SummaryNotFoundException('No Receive Available for this Item!');
        } catch (DateNotAvailableException $e) {
        }
    }

    /**
     * @throws SummaryNotFoundException
     * @throws DivisionByZeroException
     * @throws DateNotAvailableException
     */
    public function deleting(YarnReceiveReturnDetail $yarn)
    {
        try {
            $summary = $this->stockSummaryService->summary($yarn);
            $rate = $this->stockSummaryService->yarnRate($summary);

            $summary->balance += $yarn->return_qty;
            $summary->balance_amount += ($rate * $yarn->return_qty);
            $summary->receive_return_qty -= $yarn->return_qty;
            $summary->save();

            // Date wise stock summary
            $date = $yarn->receiveReturn->return_date;
            $dateWiseSummary = (new YarnDateWiseSummaryService)->getDateWiseSummary($yarn, $date);
            $totalReturnQty = $dateWiseSummary->receive_return_qty - $yarn->return_qty;

            $dateWiseSummary->update([
                'receive_return_qty' => $totalReturnQty,
                'rate' => (new YarnStockSummaryService())->getYarnRate($yarn)
            ]);

        } catch (ModelNotFoundException $e) {
            throw new SummaryNotFoundException('No Summary Available for this Item!');
        }
    }
}
