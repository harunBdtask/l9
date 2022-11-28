<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Rules\V3\TrimsStoreIssueDetail;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssue\TrimsStoreIssueDetail;
use SkylarkSoft\GoRMG\TrimsStore\Rules\V3\QtyRuleCriteria;

class IssueQtyRule extends QtyRuleCriteria implements Rule
{
    private $exceedQty;

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value): bool
    {
        $stockSummary = $this->getStockSummary();
        $balanceQty = 0;

        if ($stockSummary) {
            $receiveQty = ($stockSummary->receive_qty - $stockSummary->receive_return_qty) ?? 0;

            $issueQty = ($stockSummary->issue_qty - $stockSummary->issue_return_qty) ?? 0;

            $balanceQty = ($receiveQty - $issueQty) ?? 0;

            if (request()->has('id')) {
                $prevIssueQty = TrimsStoreIssueDetail::query()
                    ->where('id', request()->input('id'))
                    ->first()['issue_qty'] ?? 0;
                $balanceQty += $prevIssueQty;
            }
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
        return "Issue Qty Can't Be Greater Than Receive! Exceed($this->exceedQty)";
    }
}
