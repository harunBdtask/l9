<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Rules\V3\TrimsStoreReceiveReturn;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturn;
use SkylarkSoft\GoRMG\TrimsStore\Rules\V3\QtyRuleCriteria;

class ReceiveReturnQtyRule extends QtyRuleCriteria implements Rule
{
    private $balanceQty = 0;

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value): bool
    {
        $stockSummary = $this->getStockSummary();

        if ($stockSummary) {
            $prevReceiveReturnQty = TrimsStoreReceiveReturn::query()->where('id', request()->input('id'))
                ->first()['receive_return_qty'] ?? 0;

            $receiveQty = ($stockSummary->receive_qty - $stockSummary->receive_return_qty + $prevReceiveReturnQty) ?? 0;
            $issuedQty = ($stockSummary->issue_qty - $stockSummary->issue_return_qty) ?? 0;
            $this->balanceQty += ($receiveQty - $issuedQty) ?? 0;
        }

        return $this->balanceQty >= $value;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return "Available balance {$this->balanceQty} Qty, Can't return!";
    }
}
