<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Size name is required.',
            'integer' => "Must be a integer",
            'min' => "Negative value given",
            'not_in' => "Must be a positive number",
        ];
    }

    public function rules(): array
    {
        return [
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:sizes,name," . $this->segment(2),
            'sort' => 'nullable|integer|min:0|not_in:0',
        ];
    }
}
