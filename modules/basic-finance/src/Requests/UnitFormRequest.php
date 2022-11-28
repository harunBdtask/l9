<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'bf_project_id' => 'required',
            'user_ids' => 'required',
            'unit' => 'required|unique:bf_units,unit,' . $this->segment(3) . ',id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'Company field is required',
            'bf_project_id.required' => 'Project field is required',
            'unit.required' => 'Unit name field is required',
            'user_ids.required' => 'User field is required',
        ];
    }
}
