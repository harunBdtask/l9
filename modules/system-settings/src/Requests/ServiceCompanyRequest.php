<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ServiceCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => 'This Field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i"],
        ];
    }
}
