<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'user_ids' => 'required',
            'project' => 'required|unique:fi_projects,project,' . $this->segment(3) . ',id,deleted_at,NULL',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'Company field is required',
            'user_ids.required' => 'User field is required',
            'project.required' => 'Project field is required',
        ];
    }
}
