<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules\TrimsStore;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceiveDetail;

class ReceiveQtyRule implements Rule
{
    private $balanceQty;

    public function passes($attribute, $value): bool
    {
        $id = request()->input('id');
        $inventoryDetailId = request()->input('trims_inventory_detail_id');
        $bookingQty = request()->input('booking_qty');

        $previousReceiveQty = TrimsStoreReceiveDetail::query()
            ->where('trims_inventory_detail_id', $inventoryDetailId)
            ->where('id', '!=', $id)
            ->sum('receive_qty');

        $this->balanceQty = format($bookingQty) - format($previousReceiveQty);

        return format($this->balanceQty) >= format($value);
    }

    public function message(): string
    {
        return "Receive Qty Can't Be Getter Than ($this->balanceQty).";
    }
}
