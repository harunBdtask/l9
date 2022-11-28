<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class YarnTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => 'Required',
            'max'      => 'Max length exceeds'
        ];
    }

    public function rules(): array
    {
        return [
            'transfer_criteria' => ['required', Rule::in('store_to_store', 'company_to_company')],
            'factory_id'        => 'required',
            'to_factory_id'     => 'nullable',
            'transfer_date'     => 'required|date',
            'challan_no'        => 'nullable|max:30',
            'from_store_id'     => 'required',
            'to_store_id'       => 'required'
        ];
    }
}