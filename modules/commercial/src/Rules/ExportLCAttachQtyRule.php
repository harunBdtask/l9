<?php

namespace SkylarkSoft\GoRMG\Commercial\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLCDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class ExportLCAttachQtyRule implements Rule
{
    private $message = '';

    public function passes($attribute, $value): bool
    {
        $idx = explode('.', $attribute)[1];


        $purchaseOrderId = request('po_id.' . $idx);

        $purchaseOrder = PurchaseOrder::find($purchaseOrderId);

        $contractId = request()->route('contract')->id;

        $quantity = ExportLCDetail::query()
            ->where('export_lc_id', '<>', $contractId)
            ->where('po_id', $purchaseOrderId)
            ->sum('attach_qty');

        if ($purchaseOrder->po_quantity < ($quantity + $value)) {
            $this->message = 'Qty exceeds';

            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
