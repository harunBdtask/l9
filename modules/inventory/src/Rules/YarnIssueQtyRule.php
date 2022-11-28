<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisition;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisitionDetail;

class YarnIssueQtyRule extends YarnStockQtyRule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $this->setValues($value);
        $passed = true;

        $balance = $this->summary->balance;
        $totalIssueQty = $this->summary->issue_qty;
        $totalIssueReturnQty = $this->summary->issue_return_qty;

        $this->message = 'Insufficient balance!';

        /* Issue qty can not be more than requisition qty */
//        if ( $value && $this->demand_no ) {
//            $reqId = YarnRequisition::query()->where('requisition_no', $this->demand_no)->first()->id ?? '';
//            $reqQty = YarnRequisitionDetail::query()->where('yarn_requisition_id', $reqId)->first()->requisition_qty ?? 0;
//            $existingIssueQtyByCurrentRequisition = YarnIssueDetail::query()->where('demand_no', $this->demand_no)->sum('issue_qty');
//            if ($this->details_id) {
//                $existIssue = YarnIssueDetail::query()->find($this->details_id)->issue_qty;
//                $existingIssueQtyByCurrentRequisition -= $existIssue;
//            }
//            return ($reqQty - $existingIssueQtyByCurrentRequisition) >= $value;
//        }

        /* issue qty create */
        if ( !$this->details_id ) {
            return $value > 0 && $value <= $balance;
        }

        /**
         * @var YarnIssueDetail $issue
         */
        $issue = YarnIssueDetail::query()->find($this->details_id);
        $formula = $balance + $issue->issue_qty;

        /* Issue Qty Increasing */
        if ( $value > $formula ) {
            $passed = $value <= $formula;
        }

        /* Issue Qty Decreasing */
        if ( $value < $issue->issue_qty ) {
            $passed = $totalIssueQty - ($issue->issue_qty - $value) >= $totalIssueReturnQty;
        }

        return $passed;
    }
}
