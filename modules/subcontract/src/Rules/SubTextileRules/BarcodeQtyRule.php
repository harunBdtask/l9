<?php

namespace SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;

class BarcodeQtyRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $detail = SubGreyStoreReceiveDetails::query()->findOrFail(request()->input('sub_grey_store_receive_detail_id'));

        return array_sum($value) == $detail->receive_qty;
    }

    public function message(): string
    {
        return "Total roll qty and receive qty not matched";
    }
}
