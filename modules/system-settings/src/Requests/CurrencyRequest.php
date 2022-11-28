<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
{
    public function authorize() : bool
    {
        return Auth::check();
    }

    public function messages() : array
    {
        return [
            'currency_name.required' => 'Currency is required.',
        ];
    }

    public function rules(): array
    {
        return [
            'currency_name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:currencies,currency_name," . $this->segment(2),
        ];
    }
}
