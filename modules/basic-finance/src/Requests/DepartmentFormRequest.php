<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Requests;

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
            'department' => 'required|unique:bf_departments,department,' . $this->segment(3) . ',id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'department.required' => 'Department name field is required',
        ];
    }
}
