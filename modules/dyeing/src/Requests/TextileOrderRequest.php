<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TextileOrderRequest extends FormRequest
{
    public function authorize():bool
    {
        return Auth::check();
    }

    public function rules():array
    {
        return [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'fabric_sales_order_id' => 'required',
            'fabric_sales_order_no' => 'required',
            'receive_date' => 'required',
            'currency_id' => 'required',
            'textile_order_details.*.sub_textile_operation_id' => 'required',
            'textile_order_details.*.sub_textile_process_id' => 'required',
            'textile_order_details.*.fabric_sales_order_detail_id' => 'required',
            'textile_order_details.*.fabric_composition_id' => 'required',
            'textile_order_details.*.body_part_id' => 'required',
            'textile_order_details.*.item_color_id' => 'required',
            'textile_order_details.*.dia_type_id' => 'required',
            'textile_order_details.*.order_qty' => 'required',
            'textile_order_details.*.uom_id' => 'required',
            'textile_order_details.*.price_rate' => 'required',
            'textile_order_details.*.total_value' => 'required',
            'textile_order_details.*.conv_rate' => 'required',
            'textile_order_details.*.total_amount_bdt' => 'required',
            'textile_order_details.*.delivery_date' => 'required',
        ];
    }
}
