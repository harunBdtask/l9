<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ControlAccountFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_account_id' => 'required',
            'group_account_id' => 'required',
            'type_id' => 'required',
            //            'code' => 'required',
            'name' => [
                'required',
                Rule::unique('fi_accounts', 'name')
                    ->whereNull('deleted_at')
                    ->ignore($this->id),
            ]
        ];
    }
}
