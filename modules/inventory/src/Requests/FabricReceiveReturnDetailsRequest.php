<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\FabricReceiveReturnQtyRule;

class FabricReceiveReturnDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'unique_id'             => 'required|string|max:30',
            'buyer_id'              => 'required',
            'style_id'              => 'required',
            'style_name'            => 'required|string|max:60',
            'batch_no'              => 'required|string|max:60',
            'gmts_item_id'          => 'required',
            'body_part_id'          => 'required',
            'fabric_composition_id' => 'required',
            'construction'          => 'required|string|max:255',
            'fabric_description'    => 'required|string|max:255',
            'dia'                   => 'required|string',
            'gsm'                   => 'required|string',
            'dia_type'              => 'required',
            'color_id'              => 'required',
            'contrast_color_id'     => 'nullable|array',
            'uom_id'                => 'required',
            'return_qty'            => ['required', 'numeric', new FabricReceiveReturnQtyRule()],
            'rate'                  => 'required|numeric',
            'amount'                => 'required|numeric',
            'fabric_shade'          => 'nullable|string',
            'no_of_roll'            => 'nullable|numeric',
            'store_id'              => 'required',
            'floor_id'              => 'required',
            'room_id'               => 'required',
            'rack_id'               => 'required',
            'shelf_id'              => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'buyer_id.required'  => 'Buyer id is required!',
            'style_id.required' => 'Style_id is required!',
        ];
    }
}
