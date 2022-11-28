<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KnitProgramCollarCuffFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'details.*.program_item_size' => 'required',
            'details.*.excess_percentage' => 'required'
        ];
    }
}
