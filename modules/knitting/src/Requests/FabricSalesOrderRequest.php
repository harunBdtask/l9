<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FabricSalesOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'location' => 'required',
            'buyer_id' => 'required',
            'style_name' => 'required',
            'booking_no' => 'required',
            'factory_id' => 'required',
            'currency_id' => 'required',
            'within_group' => 'required',
            'booking_date' => 'required',

            'details.*.garments_item_id' => 'required',
            'details.*.body_part_id' => 'required',
            //'details.*.color_type_id' => 'required',
            'details.*.fabric_description' => 'required',
            'details.*.fabric_gsm' => 'required',
            'details.*.fabric_dia' => 'required',
            'details.*.dia_type_id' => 'required',
            'details.*.item_color_id' => 'required',
            'details.*.color_range_id' => 'required',
            'details.*.cons_uom' => 'required',
            'details.*.booking_qty' => 'required',
            'details.*.average_price' => 'required',
            'details.*.amount' => 'required',
            'details.*.prog_uom' => 'required',
            'details.*.finish_qty' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Field is required'
        ];
    }
}
