<?php

namespace SkylarkSoft\GoRMG\DyesStore\Actions;

use Exception;
use SkylarkSoft\GoRMG\DyesStore\Models\DsChemicalReceiveReturn;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;

class MakeTransactionAction
{
    private const IN = 'in';
    private const OUT = 'out';
    private const RECEIVE_RETURN = 'receive_return';
    private const ISSUE_RETURN = 'issue_return';

    /**
     * @param DsChemicalReceiveReturn $receiveReturn
     * @return void
     * @throws Exception
     */
    public function transactionDetail(DsChemicalReceiveReturn $receiveReturn)
    {
        foreach ($receiveReturn->details as $value) {
            $itemId = $value['item_id'];

            $totalReceiveQty = DyesChemicalTransaction::query()
                ->where('item_id', $itemId)
                ->where('trn_type', self::IN)
                ->sum('qty');

            $totalIssueQty = DyesChemicalTransaction::query()
                ->where('item_id', $itemId)
                ->where('trn_type', self::OUT)
                ->sum('qty');

            $totalReceiveReturnQty = DyesChemicalTransaction::query()
                ->where('item_id', $itemId)
                ->where('trn_type', self::RECEIVE_RETURN)
                ->sum('qty');

            $totalIssueReturnQty = DyesChemicalTransaction::query()
                ->where('item_id', $itemId)
                ->where('trn_type', self::ISSUE_RETURN)
                ->sum('qty');

            $actualReceiveQty = format($totalReceiveQty,4) - format($totalReceiveReturnQty,4);
            $actualIssueQty = format($totalIssueQty,4) - format($totalIssueReturnQty, 4);

            $balanceQty = format($actualReceiveQty - $actualIssueQty, 4);

            if ($balanceQty < format($value['return_qty'], 4)) {
                throw new Exception("This {$value['item_name']} Balance quantity is {$balanceQty}.");
            }

            DyesChemicalTransaction::query()->create([
                'item_id' => $value['item_id'],
                'category_id' => $value['category_id'],
                'brand_id' => $value['brand_id'] ?? null,
                'qty' => $value['return_qty'],
                'rate' => $value['rate'],
                'trn_date' => $receiveReturn->return_date,
                'trn_type' => 'receive_return',
                'ref' => null,
                'sub_store_id' => null,
                'trn_store' => null,
                'dyes_chemical_receive_id' => $receiveReturn->receive_id,
                'receive_id' => null,
                'uom_id' => $value['uom_id'],
                'sr_no' => $value['details']['sr_no'],
                'lot_no' => $value['details']['lot_no'],
                'mrr_no' => $value['details']['mrr_no'],
                'batch_no' => $value['details']['batch_no'],
                'life_end_days' => $value['details']['life_end_days'],
                'dyes_chemical_issue_id' => null,
                'dyes_chemical_receive_return_id' => $receiveReturn->id,
                'generate_barcodes' => 0,
                'barcode_id' => null,
            ]);
        }
    }
}
