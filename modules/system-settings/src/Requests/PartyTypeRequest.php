<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PartyTypeRequest extends FormRequest
{
    public function authorize() : bool
    {
        return Auth::check();
    }

    public function messages() : array
    {
        return [
            'party_type.required' => 'Party Type is required.',
        ];
    }

    public function rules() : array
    {
        return [
            'party_type' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:party_types,party_type," . $this->segment(2),
        ];
    }
}
