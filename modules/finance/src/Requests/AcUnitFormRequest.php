<?php

namespace SkylarkSoft\GoRMG\Finance\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcUnitFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ac_company_id' => 'required',
            'unit' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'ac_company_id.required' => 'Company field is required',
            'unit.required' => 'Unit field is required',
        ];
    }
}
