<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KnittingQcRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'point_calculation_method' => 'required',
            'qc_shift_id' => 'required',
            'qc_operator_id' => 'required',
            'qc_datetime' => 'required',
            'qc_roll_weight' => 'required|gt:0',
            'qc_fabric_dia' => 'required',
            'qc_fabric_gsm' => 'required',
            'qc_length_in_yards' => 'required',
            'qc_fault_details' => 'required|array',
            'qc_total_point' => 'required',
            'qc_grade_point' => 'required',
            'qc_fabric_grade' => 'required',
            'qc_status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Field is required',
            'array' => 'Array is required',
        ];
    }
}
