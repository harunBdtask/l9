<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PolyCartoonRequest extends FormRequest
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
            'buyer_id.required' => 'Buyer field is required.',
            'order_id.required' => 'Order field is required.',
            'purchase_order_id.required' => 'Order field is required.',
            'color_id.required' => 'Color field is required.',
            'poly_qty.required' => 'Poly Qty field is required.'
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
            'color_id.*' => 'required',
            'poly_qty.*' => 'required',

        ];
    }
}
