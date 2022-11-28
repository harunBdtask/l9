<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'short_name' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'short_name.required' => 'Bank short name field is required'
        ];
    }
}
