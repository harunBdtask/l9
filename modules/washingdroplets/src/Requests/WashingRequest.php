<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WashingRequest extends FormRequest
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
     * @return bool|array
     */
    public function messages()
    {
        return [
            'buyer_id.required' => 'Buyer name is required.',
            'style_id.required' => 'Style name is required.',
            'order_id.required' => 'Order no is required.',
            'color_id.required' => 'Color name is required.',
            'received_qty.required' => 'Received qty is required.',
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
            'style_id' => 'required',
            'order_id' => 'required',
            'color_id' => 'required',
            'received_qty' => 'required'
        ];
    }
}
