<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\FabricIssueQtyRule;

class FabricIssueDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'unique_id'             => 'required|string|max:30',
            'sample_type'           => 'required',
            'buyer_id'              => 'required',
            'style_id'              => 'required',
            'style_name'            => 'required|string|max:60',
            'batch_no'              => 'required|string|max:60',
            'gmts_item_id'          => 'required',
            'body_part_id'          => 'required',
            'fabric_composition_id' => 'required',
            'construction'          => 'required|string|max:255',
            'fabric_description'    => 'required|string|max:255',
            'dia'                   => 'required',
            'gsm'                   => 'required|string|max:10',
            'dia_type'              => 'required',
            'color_type_id'         => 'required',
            'color_id'              => 'required',
            'contrast_color_id'     => 'nullable|array',
            'uom_id'                => 'required',
            'issue_qty'             => ['required', 'min:0', 'not_in:0', new FabricIssueQtyRule()],
            'rate'                  => 'required|string|max:20',
            'amount'                => 'required',
            'fabric_shade'          => 'nullable|string|max:5',
            'no_of_roll'            => 'nullable|string|max:5',
            'grey_used'             => 'nullable|string|max:5',
            'store_id'              => 'required',
            'floor_id'              => 'required',
            'room_id'               => 'required',
            'rack_id'               => 'required',
            'shelf_id'              => 'required',
            'cutting_unit_no'       => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'This factory name field is required',
            'buyer_id.required'   => 'This buyer name field is required',
        ];
    }
}
