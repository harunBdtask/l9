<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YarnRequisitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'requisition_date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Field is required'
        ];
    }
}
