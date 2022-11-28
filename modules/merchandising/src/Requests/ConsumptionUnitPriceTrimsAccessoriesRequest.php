<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsumptionUnitPriceTrimsAccessoriesRequest extends FormRequest
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
            'unit_price.*.required' => 'Unit price is required',
            'unit_price.*.numeric' => 'Unit price must be numeric',
            'unit_of_measurement_id.required' => 'UOM is required',
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
            'unit_price.*' => 'required|numeric',
            'unit_of_measurement_id' => 'required',
        ];
    }
}
