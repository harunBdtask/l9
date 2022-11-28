<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrimsAccessoriesSaveNewWayRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier_id' => 'required',
            'item_id' => 'required',
            'color_type' => 'required',
            'size_type' => 'required',
            'extra_percentage' => 'required',
            'item_desc' => 'required',
            'con_uom' => 'required',
            'cost_per_unit' => 'required',
            'total_cost' => 'required',
            'budget_trim_attachment' => 'nullable|mimes:jpeg,jpg,png,gif|max:10000',
        ];
    }
}
