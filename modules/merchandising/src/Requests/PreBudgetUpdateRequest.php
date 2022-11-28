<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PreBudgetUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $preBudget = $this->route('preBudget');

        return [
            'job_number' => ['required', Rule::unique('pre_budgets')->whereNull('deleted_at')->ignore($preBudget, 'id')],
            'agent_id' => 'required',
            'buyer_id' => 'required',
            'order_no.*' => 'required',
            'image.*' => 'mimes:jpeg,bmp,png',
            'style.*' => 'required',
            'quantity.*' => 'required|numeric',
            'description.*' => 'required',
            'unit_price.*' => 'required|numeric',
            'cm.*' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'buyer_id.required' => 'Buyer is required',
            'agent_id.required' => 'Required',
            'order_no.*.required' => 'Required',
            'style.*.required' => 'Required',
            'image.*.mimes' => 'Must be image',
            'quantity.*.required' => 'Required',
            'quantity.*.numeric' => 'Should be number',
            'description.*.required' => 'Required',
            'unit_price.*.required' => 'Required',
            'unit_price.*.numeric' => 'Should be number',
            'cm.*.required' => 'Required',
            'cm.*.numeric' => 'Should be number',
            'percentage.*.required' => 'Required',
            'cm_total.*.required' => 'Required',
            'payment_mode.*.required' => 'Required',
            'shipment_mode.*.required' => 'Required',
            'supplier_name.*.required' => 'Required',
            'fabric_composition_id.*.required' => 'Required',
            'item_id.*.required' => 'Required',
        ];
    }
}
