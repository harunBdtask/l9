<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueOperatorCode;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueOperatorName;

class OperatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'operator_name.required' => 'This Field is required.',
            'operator_type.required' => 'This Field is required.',
            'operator_code.required' => 'This Field is required.',
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
            'operator_name' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", new UniqueOperatorName()],
            'operator_type' => 'required',
            'operator_code' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", 'max:10', new UniqueOperatorCode()],
        ];
    }
}
