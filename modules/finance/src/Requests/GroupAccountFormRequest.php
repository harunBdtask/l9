<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupAccountFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_account_id' => 'required',
            'type_id' => 'required',
            //            'code' => 'required',
            'name' => [
                'required',
                Rule::unique('fi_accounts', 'name')->ignore($this->id),
            ]
        ];
    }
}
