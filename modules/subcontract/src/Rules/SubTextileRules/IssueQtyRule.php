<?php

namespace SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssueDetail;

class IssueQtyRule extends QtyRuleCriteria implements Rule
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
        $receiveQty = (($stockSummary->receive_qty + $stockSummary->receive_transfer_qty) -
            $stockSummary->receive_return_qty - $stockSummary->transfer_qty) ?? 0;

        $issueQty = ($stockSummary->issue_qty - $stockSummary->issue_return_qty) ?? 0;

        $balanceQty = ($receiveQty - $issueQty) ?? 0;

        if (request()->has('id')) {
            $prevIssueQty = SubGreyStoreIssueDetail::query()
                ->where('id', request()->input('id'))
                ->first()['issue_qty'] ?? 0;
            $balanceQty += $prevIssueQty;
        }

        $this->exceedQty = abs($balanceQty - $value);

        return $value <= $balanceQty;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "Issue Qty Can't Be Greater Than Receive.Exceed($this->exceedQty)";
    }
}
