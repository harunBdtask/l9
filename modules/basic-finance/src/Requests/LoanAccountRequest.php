<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'project_id' => 'required',
            'unit_id' => 'required',
            'bank_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'Company name field is required',
            'bank_id.required' => 'Bank name field is required',
        ];
    }
}
