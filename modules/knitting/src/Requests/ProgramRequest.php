<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'knitting_source_id' => 'required',
            'knitting_party_id' => 'required',
            'program_date' => 'required',
            'machine_dia' => 'required',
            'machine_gg' => 'required',
            'finish_fabric_dia' => 'required',
            'status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute is required',
        ];
    }
}
