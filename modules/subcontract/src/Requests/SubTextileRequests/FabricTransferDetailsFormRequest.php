<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\SubTextileRequests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules\TransferQtyRule;

class FabricTransferDetailsFormRequest extends FormRequest
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
            'from_store_id' => 'required',
            'from_order_id' => 'required',
            'from_order_detail_id' => 'required',
            'to_store_id' => 'required',
            'to_order_id' => 'required',
            'to_order_detail_id' => 'required',
            'transfer_qty' => ['required', new TransferQtyRule()],
        ];
    }
}
