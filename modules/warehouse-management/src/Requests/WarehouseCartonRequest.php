<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseCartonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return bool
     */
    public function messages()
    {
        return [
            'buyer_id.required' => 'This field is required.',
            'order_id.required' => 'This field is required.',
            'purchase_order_id.required' => 'This field is required.',
            'garments_qty.required' => 'This field is required.',
            'color_id.*.required' => 'This field is required.',
            'size_id.*.required' => 'This field is required.',
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
            'buyer_id' => 'required',
            'order_id' => 'required',
            'purchase_order_id' => 'required',
            'garments_qty' => 'required|integer',
            'color_id.*' => 'required',
            'size_id.*' => 'required',
        ];
    }
}
