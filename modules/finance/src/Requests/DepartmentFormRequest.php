<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'department' => 'required|unique:fi_departments,department,' . $this->segment(3) . ',id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'department.required' => 'Department name field is required',
        ];
    }
}
