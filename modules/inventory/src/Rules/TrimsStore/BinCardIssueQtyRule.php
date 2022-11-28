<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules\TrimsStore;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCardDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrrDetail;

class BinCardIssueQtyRule implements Rule
{
    private $balanceQty;
    private $uomValue;

    public function passes($attribute, $value): bool
    {
        $id = request()->input('id');
        $mrrDetailId = request()->input('trims_store_mrr_detail_id');
        $this->uomValue = request()->input('uom_value');

        $mrrDetailQty = TrimsStoreMrrDetail::query()->findOrFail($mrrDetailId)['total_delivered_qty'] ?? 0;

        $previousBinCardQty = TrimsStoreBinCardDetail::query()
            ->where('trims_store_mrr_detail_id', $mrrDetailId)
            ->where('id', '!=', $id)
            ->sum('issue_qty');

        $this->balanceQty = format($mrrDetailQty) - format($previousBinCardQty);

        return $this->balanceQty >= $value;
    }

    public function message(): string
    {
        return "BinCard Qty Can't Be Getter Than ($this->balanceQty) $this->uomValue.";
    }
}
