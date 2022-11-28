<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FabricRequisitionRequestNew extends FormRequest
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

    public function messages()
    {
        return [
            'body_part_fabric.*.required' => 'Quantity is required.',
            'color.*.required' => 'Quantity is required.',
            'construction_fabric.*.required' => 'Quantity is required.',
        ];
    }

    public function rules()
    {
        return [
            'po_no' => 'required',
            'item_group' => 'required',
            'garments_item' => 'required',
            'color_type' => 'required',
            'body_part_fabric.*' => 'required',
            'color.*' => 'required',
            'construction_fabric.*' => 'required',
            'composition_fabric.*' => 'required',
            'gsm.*' => 'required',
            'yarn_count.*' => 'required',
            'plan_cut.*' => 'required',
            'process_loss.*' => 'required',
            'gray_fab_qty.*' => 'required',
            'finish_fab_qty.*' => 'required',
            'wash_method.*' => 'required',
            'fabric_rate.*' => 'required',
            'fabric_amount.*' => 'required',
            // yarn part
            'body_part_yarn.*' => 'required',
            'count.*' => 'required',
            'composition_yarn_one.*' => 'required',
//            'composition_yarn_one_id.*' => 'required',
            'composition_yarn_one_percentage.*' => 'required',
            'composition_yarn_two.*' => 'required',
//            'composition_yarn_two_id.*' => 'required',
            'composition_yarn_two_percentage.*' => 'required',
            'yarn_type.*' => 'required',
            'consumption_qty.*' => 'required',
            'avg_consumption_qty.*' => 'required',
            'supplier_id.*' => 'required',
            'yarn_rate.*' => 'required',
            'yarn_amount.*' => 'required',
        ];
    }
}
