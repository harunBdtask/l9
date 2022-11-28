<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FabriceSalesOrderDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fabric_composition_id' => 'required',
            'gray_qty'              => 'required',
            'fabric_sales_order_id' => 'required',
            'garments_item_id'      => 'required',
            'body_part_id'          => 'required',
            'color_type_id'         => 'required',
            'fabric_description'    => 'required',
            'fabric_gsm'            => 'required',
            'fabric_dia'            => 'required',
            'color_range'           => 'required',
            'average_price'         => 'required',
            'amount'                => 'required',
            'finish_qty'             => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'required' => 'Field is required'
        ];
    }


}
