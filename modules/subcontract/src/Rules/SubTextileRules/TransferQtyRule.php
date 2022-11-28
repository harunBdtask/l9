<?php

namespace SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreFabricTransferDetail;

class TransferQtyRule extends TransferQtyRuleCriteria implements Rule
{
    private $exceedQty;

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
            $actualReceiveQty = ($stockSummary->receive_qty + $stockSummary->receive_transfer_qty) -
                $stockSummary->receive_return_qty - $stockSummary->transfer_qty;

            $actualIssueQty = ($stockSummary->issue_qty - $stockSummary->issue_return_qty) ?? 0;

            $balanceQty = $actualReceiveQty - $actualIssueQty;

            if (request()->has('id')) {
                $prevTransferQty = SubGreyStoreFabricTransferDetail::query()
                    ->where('id', request()->input('id'))
                    ->first()['transfer_qty'] ?? 0;

                $balanceQty += $prevTransferQty;
            }

            $this->exceedQty = $balanceQty - $value;

            return $balanceQty >= $value;
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
        return "Transfer Qty Can't Be Greater Than Receive.Exceed($this->exceedQty)";
    }
}
