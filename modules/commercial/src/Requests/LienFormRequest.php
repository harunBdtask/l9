<?php

namespace SkylarkSoft\GoRMG\Commercial\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LienFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'bank_id' => 'required',
            'lien_date' => 'required',
            'factory_id' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            "required" => "Required"
        ];
    }
}
