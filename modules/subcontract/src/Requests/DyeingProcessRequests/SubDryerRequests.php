<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubDryerRequests extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

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
}
