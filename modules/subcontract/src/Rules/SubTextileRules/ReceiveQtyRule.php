<?php

namespace SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;

class ReceiveQtyRule extends QtyRuleCriteria implements Rule
{
    private $alreadyIssuedQty;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $stockSummary = $this->getStockSummary();
        if ($stockSummary) {
            $this->alreadyIssuedQty = ($stockSummary->issue_qty - $stockSummary->issue_return_qty) ?? 0;
            $receiveQty = ($stockSummary->receive_qty - $stockSummary->receive_return_qty) ?? 0;
            $previousReceiveQty = SubGreyStoreReceiveDetails::query()
                                      ->where('id', request()->input('id'))
                                      ->first()['receive_qty'];

            $receiveQty -= $previousReceiveQty;
            $receiveQty += $value;

            return $receiveQty >= $this->alreadyIssuedQty;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "Receive Qty Can't Less Than Already Issued($this->alreadyIssuedQty).";
    }
}
