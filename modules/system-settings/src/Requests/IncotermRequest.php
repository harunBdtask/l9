<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class IncotermRequest extends FormRequest
{
    public function authorize() : bool
    {
        return Auth::check();
    }

    public function messages() : array
    {
        return [
            'incoterm.required' => 'Incoterm is required.',
        ];
    }

    public function rules() : array
    {
        return [
            'incoterm' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:incoterms,incoterm," .$this->segment(2) ,
        ];
    }
}
