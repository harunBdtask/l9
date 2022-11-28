<?php

namespace SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;

class ReceiveReturnQtyRule extends QtyRuleCriteria implements Rule
{
    private $balanceQty = 0;

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        $stockSummary = $this->getStockSummary();
        if ($stockSummary) {
            $prevReceiveReturnQty = SubGreyStoreReceiveDetails::query()->where('id', request()->input('id'))
                                        ->first()['receive_return_qty'] ?? 0;

            $receiveQty = ($stockSummary->receive_qty - $stockSummary->receive_return_qty + $prevReceiveReturnQty) ?? 0;
            $issuedQty = ($stockSummary->issue_qty - $stockSummary->issue_return_qty) ?? 0;
            $this->balanceQty += ($receiveQty - $issuedQty) ?? 0;
        }

        return $this->balanceQty >= $value;
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return "Available balance {$this->balanceQty} Qty, Can't return!";
    }
}
