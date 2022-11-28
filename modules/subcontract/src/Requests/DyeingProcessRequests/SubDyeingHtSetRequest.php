<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests;

use Illuminate\Foundation\Http\FormRequest;

class SubDyeingHtSetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'supplier_id' => 'required',
            'entry_basis' => 'required',
            'batch_id' => 'required_if:entry_basis,1',
            'order_id' => 'required_if:entry_basis,2',
            'production_date' => 'required',
            'details' => 'required',
            'details.*.fin_no_of_roll' => 'numeric|gte:0',
            'details.*.finish_qty' => 'numeric|gte:0',
            'details.*.unit_cost' => 'numeric|gte:0',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
