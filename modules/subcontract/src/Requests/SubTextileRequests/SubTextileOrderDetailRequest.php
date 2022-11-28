<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests;

use Illuminate\Foundation\Http\FormRequest;

class SubTextileOrderDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function messages(): array
    {
        return [
            'required' => 'This field is required',
            'date' => 'Must be a date',
        ];
    }

    public function rules(): array
    {
        return [
            'uuid' => 'required',
            'factory_id' => 'required',
            'supplier_id' => 'required',
            'order_no' => 'required',
            'sub_textile_order_id' => 'required',
            'sub_textile_operation_id' => 'required',
            'sub_textile_process_id' => 'required',
            'fabric_composition_id' => 'required',
            'fabric_type_id' => 'required',
            'color_id' => 'required',
            'color_type_id' => 'required',
            'finish_dia' => 'required',
            'dia_type_id' => 'required',
            'gsm' => 'required',
            'fabric_description' => 'required',
            'order_qty' => 'required',
            'unit_of_measurement_id' => 'required',
            'price_rate' => 'required',
            'currency_id' => 'required',
            'total_value' => 'required',
            'conv_rate' => 'required',
            'total_amount_bdt' => 'required',
            'remarks' => 'nullable',
            'ld_no' => 'nullable',
            'operation_description' => 'nullable',
            'body_part_id' => 'nullable',
            'yarn_details' => 'nullable',
            'customer_buyer' => 'nullable',
            'customer_style' => 'nullable',
        ];
    }
}
