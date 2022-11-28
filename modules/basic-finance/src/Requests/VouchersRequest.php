<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\BasicFinance\Rules\VoucherNoUniqueRule;

class VouchersRequest extends FormRequest
{
    public function authorized(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'voucher_no' => ['required', new VoucherNoUniqueRule()],
            'type_id' => 'required',
            'trn_date' => 'required',
            'project_id' => 'required',
            'unit_id' => 'required',
            'currency_id' => 'required',
            'details' => 'required',
        ];
    }
}
