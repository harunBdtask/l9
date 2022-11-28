<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FabricIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id'                      => 'required',
            'issue_purpose'                   => 'required|string|max:40',
            'challan_no'                      => 'nullable|string|max:40',
            'cutt_req_no'                     => 'nullable|string|max:255',
            'issue_date'                      => 'required',
            'service_source'                  => 'required|in:in_house,out_house',
            'buyer_id'                        => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'This factory name field is required',
            'buyer_id.required'   => 'This buyer name field is required',
        ];
    }
}
