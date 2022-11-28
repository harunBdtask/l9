<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
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
     * Get the validation messages that apply to the erroneous request.
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'factory_id.required' => 'Factory field is required.',
            'user_id.required' => 'User field is required.',
            'module_id.required' => 'Module field is required.',
            'menu_id.required' => 'Menu field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'factory_id' => 'required',
            'user_id' => 'required',
            'module_id' => 'required',
            'menu_id' => 'required',
            'permission_id*' => 'required',
        ];
    }
}
