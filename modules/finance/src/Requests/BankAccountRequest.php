<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

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
            'account_number' => 'required',
            'currency_type_id' => 'required',
            'control_account_id' => 'required',
            'ledger_name' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'Company name field is required',
            'unit_id.required' => 'Unit name field is required',
            'bank_id.required' => 'Bank name field is required',
            'currency_type_id.required' => 'Currency Type field is required',
        ];
    }
}
