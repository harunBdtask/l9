<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\BasicFinance\Models\Voucher;

class VoucherNoUniqueRule implements Rule
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
        $editId = request()->route()->id;
        $voucherType = Voucher::VOUCHER_TYPE[request('type_id')];
        $voucherNo = Voucher::generateVoucherNo($voucherType);

        if ($editId) {
            $currentVoucher = Voucher::query()->findOrFail($editId);
            $response = $currentVoucher->voucher_no === $value;
        } else {
            $response = $voucherNo === $value;
        }

        return $response;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Voucher No. not matched';
    }
}
