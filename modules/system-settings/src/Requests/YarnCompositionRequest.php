<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class YarnCompositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages() : array
    {
        return [
            'yarn_composition.required' => 'Yarn Composition is required.',
        ];
    }

    public function rules() : array
    {
        return [
            'yarn_composition' => "required|not_regex:/([^\w\d\s&'.\-\_\%)\(\/])+/i|unique:yarn_compositions,yarn_composition," . $this->segment(2),
        ];
    }
}
