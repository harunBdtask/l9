<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcDepartmentFormRequest extends FormRequest
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
            'name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'ac_company_id.required' => 'Company field is required',
            'ac_unit_id.required' => 'Unit field is required',
            'name.required' => 'Department name field is required',
        ];
    }
}
