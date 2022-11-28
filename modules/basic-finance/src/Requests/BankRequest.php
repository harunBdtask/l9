<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\BasicFinance\Rules\UniqueBankRule;

class BankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', new UniqueBankRule()],
            'short_name' => 'required',
            'bank_contract_details.*.name' => 'required',
            'bank_contract_details.*.designation' => 'required',
            'bank_contract_details.*.contract_number' => 'numeric',
            'bank_contract_details.*.email' => 'email',
            // 'branch_name' => 'required',
            // 'currency_type_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bank name field is required',
            'short_name.required' => 'Bank short name field is required',
            // 'branch_name.required' => 'Branch name field is required',
            // 'currency_type_id.required' => 'Bank type field is required',
        ];
    }
}
