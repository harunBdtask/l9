<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LedgerAccountFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_type' => 'required',
            'parent_account_id' => 'required',
            'group_account_id' => 'required',
            'control_account_id' => 'required_if:account_type,==,4',
            'type_id' => 'required',
            'name' => [
                'required_if:account_type,==,4',
                Rule::unique('fi_accounts', 'name')->ignore($this->id),
            ]
        ];
    }
}
