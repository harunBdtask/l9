<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ColorTypeRequest extends FormRequest
{
    public function authorize() : bool
    {
        return Auth::check();
    }

    public function rules() : array
    {
        return [
            'color_types' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", Rule::unique('color_types')->ignore($this->segment(2))->whereNull('deleted_at')],
        ];
    }
}
