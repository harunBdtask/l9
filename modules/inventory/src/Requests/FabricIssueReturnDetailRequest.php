<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\FabricIssueReturnQtyRule;

class FabricIssueReturnDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unique_id' => 'required|string|max:30',
            'buyer_id' => 'required',
            'style_id' => 'required',
            'style_name' => 'required|string|max:60',
            'batch_no' => 'required|string|max:60',
            'gmts_item_id' => 'required',
            'body_part_id' => 'required',
            'fabric_composition_id' => 'required',
            'construction' => 'required|string|max:255',
            'fabric_description' => 'required|string|max:255',
            'dia' => 'required|string|max:10',
            'gsm' => 'required|string|max:10',
            'dia_type' => 'required',
            'color_id' => 'required',
            'contrast_color_id' => 'nullable|array',
            'uom_id' => 'required',
            'return_qty' => ['required', 'numeric', new FabricIssueReturnQtyRule()],
            'rate' => 'required|numeric',
            'amount' => 'required',
            'reject_qty' => 'nullable|numeric',
            'fabric_shade' => 'nullable|string|max:5',
            'no_of_roll' => 'nullable|numeric',
            'grey_used' => 'nullable|string|max:5',
            'store_id' => 'required',
            'floor_id' => 'required',
            'room_id' => 'required',
            'rack_id' => 'required',
            'shelf_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'gmts_item_id.required' => 'This garments item name field is required',
            'body_part_id.required' => 'This body part field is required',
            'fabric_composition_id.required' => 'This fabric composition field is required',
            'color_id.required' => 'This color field is required',
            'store_id.required' => 'This store name field is required',
            'floor_id.required' => 'This floor name field is required',
            'room_id.required' => 'This room name field is required',
            'rack_id.required' => 'This rack name field is required',
            'shelf_id.required' => 'This shelf name field is required',
        ];
    }
}
