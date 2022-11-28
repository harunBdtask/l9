<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueParty;

class PartyRequest extends FormRequest
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
            'party_name.required' => 'This Field is required.',
            'party_type_id.required' => 'This Field is required.',
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
            'party_name' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", new UniqueParty()],
            'party_type_id' => 'required',
        ];
    }
}
