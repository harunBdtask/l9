<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests;

use Illuminate\Foundation\Http\FormRequest;

class SubDyeingBatchDetailsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'sub_textile_order_id' => ['required'],
            'sub_textile_order_detail_id' => ['required'],
            'sub_grey_store_id' => ['required'],
            'sub_dyeing_unit_id' => ['required'],
            'sub_textile_operation_id' => ['required'],
            'sub_textile_process_id' => ['required'],
            'fabric_composition_id' => ['required'],
            'fabric_type_id' => ['required'],
            'color_id' => ['required'],
//            'ld_no' => ['required'],
            'color_type_id' => ['required'],
            'finish_dia' => ['required'],
            'dia_type_id' => ['required'],
            'gsm' => ['required'],
            'material_description' => ['required'],
//            'yarn_details' => ['required'],
//            'grey_required_qty' => ['required'],
            'unit_of_measurement_id' => ['required'],
//            'stitch_length' => ['required'],
            'batch_roll' => ['required'],
            'issue_qty' => ['required'],
            'batch_weight' => ['required'],
        ];
    }
}
