<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnIssueReturnQtyRule extends YarnStockQtyRule
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

        $this->message = 'Insufficient balance!';

        $issue = YarnIssueDetail::query()->find(request('yarn_issue_detail_id'));
        if ( $value > $this->returnQty($issue->toArray()) ) {
            $passed = false;
        }
        return $passed;
    }


    public function returnQty($yarn)
    {
        $previousIssueReturnQty = YarnIssueReturnDetail::query()
            ->where('yarn_issue_detail_id', $yarn['id'])
            ->where('yarn_count_id', $yarn['yarn_count_id'])
            ->where('yarn_composition_id', $yarn['yarn_composition_id'])
            ->where('yarn_type_id', $yarn['yarn_type_id'])
            ->where('yarn_color', $yarn['yarn_color'])
            ->where('yarn_brand', $yarn['yarn_brand'])
            ->where('yarn_lot', $yarn['yarn_lot'])
            ->where('store_id', $yarn['store_id'])
            ->where('uom_id', $yarn['uom_id'])
            ->sum('return_qty');

        return $yarn['issue_qty'] - $previousIssueReturnQty;
    }
}
