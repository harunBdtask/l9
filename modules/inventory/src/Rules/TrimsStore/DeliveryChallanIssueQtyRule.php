<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules\TrimsStore;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreDeliveryChallan\TrimsStoreDeliveryChallanDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreIssue\TrimsStoreIssueDetail;

class DeliveryChallanIssueQtyRule implements Rule
{
    private $balanceQty;

    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $id = request()->input('id');
        $trimsIssueQtyDetailId = request()->input('trims_store_issue_detail_id');

        $binCardDetailQty = TrimsStoreIssueDetail::query()->findOrFail($trimsIssueQtyDetailId)['issue_qty'] ?? 0;

        $previousIssueQty = TrimsStoreDeliveryChallanDetail::query()
            ->where('trims_store_issue_detail_id', $trimsIssueQtyDetailId)
            ->where('id', '!=', $id)
            ->sum('issue_qty');
        
        $this->balanceQty = format($binCardDetailQty) - format($previousIssueQty);
        return $this->balanceQty >= $value;
    }

    public function message(): string
    {
        return "Issue Qty Can't Be Getter Than ($this->balanceQty)";
    }
}
