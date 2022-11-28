<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackingListV2Request extends FormRequest
{
    public function rules(): array
    {
        return [
            'details' => 'required',
            'details.*.no_of_carton' => 'nullable|numeric',
            'details.*.qty_per_carton' => 'nullable|numeric',
            'details.*.no_of_boxes' => 'nullable|numeric',
            'details.*.blister_kit_carton' => 'nullable|numeric',
            'details.*.kit_bc_carton' => 'nullable|numeric',
            'details.*.carton_no_from' => 'nullable|numeric',
            'details.*.carton_no_to' => 'nullable|numeric',
            'details.*.measurement_l' => 'nullable|numeric',
            'details.*.measurement_w' => 'nullable|numeric',
            'details.*.measurement_h' => 'nullable|numeric',
            'details.*.bc_height' => 'nullable|numeric',
            'details.*.gw_box_weight' => 'nullable|numeric',
            'details.*.bc_gw' => 'nullable|numeric',
            'details.*.nw_box_weight' => 'nullable|numeric',
            'details.*.bc_nw' => 'nullable|numeric',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
