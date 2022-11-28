<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueMachineNo;

class MachineRequest extends FormRequest
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
            'machine_no.required' => 'This Field is required.',
            'machine_name.required' => 'This Field is required.',
            'machine_type.required' => 'This Field is required.',
            'machine_dia.required' => 'This Field is required.',
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
            'machine_no' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", new UniqueMachineNo()],
            'machine_name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
            'machine_type' => 'required',
            'machine_rpm' => 'nullable|numeric',
            'machine_dia' => 'required|numeric',
        ];
    }
}
