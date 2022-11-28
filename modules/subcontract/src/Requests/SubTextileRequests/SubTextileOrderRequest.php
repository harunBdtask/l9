<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules\UniqueSubTextileOrderNoRule;

class SubTextileOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function messages(): array
    {
        return [
            'required' => 'This field is required',
            'date' => 'Must be a date',
        ];
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'supplier_id' => 'required',
            'order_no' => ['required', new UniqueSubTextileOrderNoRule()],
            'ref_no' => 'nullable',
            'repeat_order_no' => 'nullable',
            'description' => 'nullable',
            'revised_no' => 'nullable',
            'receive_date' => 'required|date',
            'currency_id' => 'required',
            'payment_basis' => 'required',
            'remarks' => 'nullable',
        ];
    }
}
