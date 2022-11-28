<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\FabricReceiveReturnQtyRule;

class FabricReceiveReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id'                      => 'required',
            'return_date'                     => 'required|date',
            // 'mrr_no'                          => 'required|string|max:255', // dont know mrr_no
            'returned_to_id'                  => 'required',
            // 'details.*.unique_id'             => 'required|string|max:30',
            // 'details.*.buyer_id'              => 'required',
            // 'details.*.style_id'              => 'required',
            // 'details.*.style_name'            => 'required|string|max:60',
            // 'details.*.batch_no'              => 'required|string|max:60',
            // 'details.*.gmts_item_id'          => 'required',
            // 'details.*.body_part_id'          => 'required',
            // 'details.*.fabric_composition_id' => 'required',
            // 'details.*.construction'          => 'required|string|max:255',
            // 'details.*.fabric_description'    => 'required|string|max:255',
            // 'details.*.dia'                   => 'required|string|max:10',
            // 'details.*.gsm'                   => 'required|string|max:10',
            // 'details.*.dia_type'              => 'required',
            // 'details.*.color_id'              => 'required',
            // 'details.*.contrast_color_id'     => 'nullable|array',
            // 'details.*.uom_id'                => 'required',
            // 'details.*.return_qty'            => ['required', 'numeric', 'string', 'max:20', new FabricReceiveReturnQtyRule()],
            // 'details.*.rate'                  => 'required|numeric|string|max:20',
            // 'details.*.amount'                => 'required|numeric|string|max:20',
            // 'details.*.fabric_shade'          => 'nullable|string|max:5',
            // 'details.*.no_of_roll'            => 'nullable|numeric|string|max:5',
            // 'details.*.store_id'              => 'required',
            // 'details.*.floor_id'              => 'required',
            // 'details.*.room_id'               => 'required',
            // 'details.*.rack_id'               => 'required',
            // 'details.*.shelf_id'              => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required'  => 'Company is required!',
            'return_date.required' => 'Return Date is required!',
            'return_date.date'     => 'Must be valid date!',
        ];
    }
}