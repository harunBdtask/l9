<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class YarnIssueReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            'factory_id'         => 'required',
            'issue_return_basis' => 'required',
            'issue_no'           => 'required',
            'location'           => 'nullable|string|max:255',
            'return_source'      => ['required', Rule::in(1, 2)],
            'return_date'        => 'required',
            'requisition_no'     => 'nullable',
            'return_challan'     => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required'
        ];
    }
}