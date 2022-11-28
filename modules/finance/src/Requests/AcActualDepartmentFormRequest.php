<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcActualDepartmentFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ac_company_id' => 'required',
            'ac_unit_id' => 'required',
            'ac_cost_center_id' => 'required',
            'name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'ac_cost_center_id.required' => 'Cost Center field is required',
            'ac_company_id.required' => 'Company field is required',
            'ac_unit_id.required' => 'Project field is required',
            'name.required' => 'Department name field is required',
        ];
    }
}
