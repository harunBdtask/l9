<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubDyeingGoodsDeliveryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'supplier_id' => 'required',
            'entry_basis' => 'required',
            'batch_id' => 'required_if:entry_basis,1',
            'order_id' => 'required_if:entry_basis,2',
            'delivery_date' => 'required',
            'details' => 'required',
            'details.*.total_roll' => 'numeric|gte:0',
            'details.*.delivery_qty' => 'numeric|gte:0',
        ];
    }

    public function authorize(): bool
    {
        return Auth::check();
    }
}
