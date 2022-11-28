<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests;

use Illuminate\Foundation\Http\FormRequest;

class FabricTransferFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function messages(): array
    {
        return [
            'required' => 'This field is required',
        ];
    }

    public function rules(): array
    {
        return [
            'criteria' => 'required',
            'from_company' => 'required',
            'transfer_date' => 'required',
            'transfer_type' => 'required',
            'challan_no' => 'required',
            'ready_to_approve' => 'required',
        ];
    }
}
