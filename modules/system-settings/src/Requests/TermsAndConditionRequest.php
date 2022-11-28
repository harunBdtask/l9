<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TermsAndConditionRequest extends FormRequest
{

    public function authorize() : bool
    {
        return true;
    }

    public function messages() : array
    {
        return [
            'page_name.required' => 'Factory is required',
        ];
    }

    public function rules() : array
    {
        return [
            'page_name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
        ];
    }
}
