<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FabricBookingRequest extends FormRequest
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
        $rule = [
            'attachment' => 'nullable|mimes:jpeg,jpg,png,gif|max:10000',
            'size_id.*' => 'required',
            'source_id.*' => 'required',
            'fabric_gsm.*' => 'required',
            'finish_dia.*' => 'required',
            'cutable_dia.*' => 'required',
            'finish_type.*' => 'required',
            'consumption.*' => 'required',
            'process_loss.*' => 'required',
            'part_wise_qty.*' => 'required',
            'actual_req_qty.*' => 'required',
            'fabric_type_id.*' => 'required',
            'garments_part_id.*' => 'required',
            'unit_consumption.*' => 'required',
            'total_fabric_qty.*' => 'required',
            'garments_color_id.*' => 'required',
            'composition_fabric_id.*' => 'required',
        ];


        foreach (request()->source_id as $value) {
            if ($value == 1) {
                $rule["unit_price.*"] = 'required';
                $rule["total_price.*"] = 'required';
            }
        }

        return $rule;
    }
}
