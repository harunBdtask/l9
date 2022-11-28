<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrimsAccessoriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'order_id.required' => 'Booking no is required.',
            'budget_master_id.' => 'Budget master is required.',
            'supplier_id.required' => 'Supplier is required.',
            'break_down_type.required' => 'This is required.',
            'item_id.required' => 'Item is required.',
            'general_percentage.required' => 'Item is required',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_id' => 'required',
            'budget_master_id' => 'required',
            'supplier_id' => 'required',
            'break_down_type' => 'required|numeric',
            'item_id' => 'required',
         //   'general_percentage' => 'nullable|numeric|required'
        ];
    }
}
