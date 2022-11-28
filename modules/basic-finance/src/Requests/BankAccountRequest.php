<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountRequest extends FormRequest
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
            'date' => 'required',
            'branch_name' => 'required',
            'contract_person' => 'required',
            'contract_number' => 'required',
            'contract_email' => 'required|email',
            'account_number' => 'required',
            'currency_type_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'Company name field is required',
            'bank_id.required' => 'Bank name field is required',
            'currency_type_id.required' => 'Currency Type field is required',
        ];
    }
}
