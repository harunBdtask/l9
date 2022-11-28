<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SampleTrimsBookingFormRequest extends FormRequest
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
            'factory_id.required' => 'Factory is required.',
            'buyer_id.required' => 'Buyer is required.',
            'location.required' => 'Location is required.',
            'supplier_id.required' => 'Supplier is required.',
            'material_source.required' => 'Material source is required.',
            'pay_mode.required' => 'Pay mode is required.',
            'source.required' => 'Source is required',
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
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'location' => 'required',
            'material_source' => 'required',
            'pay_mode' => 'required',
            'source' => 'required',
        ];
    }
}
