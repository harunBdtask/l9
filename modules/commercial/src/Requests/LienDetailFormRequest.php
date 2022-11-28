<?php

namespace SkylarkSoft\GoRMG\Commercial\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LienDetailFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'lien_id' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            "required" => "Required"
        ];
    }
}
