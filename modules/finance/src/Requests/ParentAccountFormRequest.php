<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ParentAccountFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_id' => 'required',
            //            'code' => 'required',
            'name' => [
                'required',
                Rule::unique('fi_accounts', 'name')->ignore($this->id),
            ]
        ];
    }
}
