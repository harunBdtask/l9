<?php

namespace SkylarkSoft\GoRMG\DyesStore\Actions;

use Exception;
use SkylarkSoft\GoRMG\DyesStore\Models\DsChemicalIssueReturn;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;

class IssueReturnTransactionAction
{
    private const OUT = 'out';
    private const ISSUE_RETURN = 'issue_return';

    public function transactionDetail(DsChemicalIssueReturn $chemicalIssueReturn)
    {
        foreach($chemicalIssueReturn->details as $value){

            $itemId = $value['item_id'];

            $totalIssueInQty = DyesChemicalTransaction::query()
                ->where('item_id', $itemId)
                ->where('trn_type', self::OUT)
                ->sum('qty');

            $totalIssueReturnQty = DyesChemicalTransaction::query()
                ->where('item_id', $itemId)
                ->where('trn_type', self::ISSUE_RETURN)
                ->sum('qty');

            $balanceQty = $totalIssueInQty - $totalIssueReturnQty;

            if ($balanceQty < $value['return_qty']) {
                throw new Exception("This {$value['item_name']} Balance quantity is {$balanceQty}.");
            }

            DyesChemicalTransaction::query()->create([
                'item_id' => $value['item_id'] ?? null,
                'category_id' => $value['category_id'] ?? null,
                'brand_id' => $value['brand_id'] ?? null,
                'qty' => $value['return_qty'] ?? null,
                'rate' => $value['rate'] ?? null,
                'trn_date' => $chemicalIssueReturn->return_date ?? null,
                'trn_type' => 'issue_return',
                'ref' => null,
                'sub_store_id' => null,
                'trn_store' => null,
                'dyes_chemical_receive_id' => null,
                'receive_id' => null,
                'uom_id' => $value['uom_id'] ?? null,
                'sr_no' => $value['sr_no'] ?? null,
                'lot_no' => $value['lot_no'] ?? null,
                'mrr_no' => $value['mrr_no'] ?? null,
                'batch_no' => $value['batch_no'] ?? null,
                'life_end_days' => $value['life_end_days'] ?? null,
                'dyes_chemical_issue_id' => $chemicalIssueReturn->issue_id ?? null,
                'dyes_chemical_issue_return_id' => $chemicalIssueReturn->id ?? null,
                'generate_barcodes' => 0,
                'barcode_id' => null,
            ]);
        }
    }
}
