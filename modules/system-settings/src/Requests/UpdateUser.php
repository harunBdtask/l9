<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class UpdateUser extends FormRequest
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
    public function messages(): array
    {
        return [
            'factory_id.required' => 'Factory field is required.',
            'role_id.required' => 'Role field is required.',
            'required' => 'This field is required'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => 'required|max:50',
            'department' => 'required|numeric',
            'factory_id' => 'required|numeric',
            'role_id' => 'required|numeric',
            'dashboard_version' => 'nullable',
            'email' => 'required|max:55|unique:users,email,' . request()->route('id'),
        ];
        if (\getRole() != 'super-admin') {
            $rules['password'] = 'required|min:5';
            $rules['confirm_password'] = 'required|same:password';
        }

        return $rules;
    }
}
