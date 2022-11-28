<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Inventory\Rules\FabricIssueReturnQtyRule;
use SkylarkSoft\GoRMG\Inventory\Rules\FabricReceiveQtyRule;

class FabricIssueReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id'                      => 'required',
            'return_date'                     => 'required|date',
//            'issue_no'                        => 'required|string|max:20',
            'challan_no'                      => 'required|string|max:40',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'This factory name field is required',
        ];
    }
}
