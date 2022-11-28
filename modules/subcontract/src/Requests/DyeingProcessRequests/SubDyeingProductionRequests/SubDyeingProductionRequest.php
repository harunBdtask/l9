<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\SubDyeingProductionRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubDyeingProductionRequest extends FormRequest
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
            'order_id' => 'required',
            'batch_id' => 'required',
            'production_date' => 'required',
            'loading_date' => 'required',
            'unloading_date' => 'required',
            'details' => 'required',
            'details.*.no_of_roll' => 'numeric|gte:0',
            'details.*.dyeing_production_qty' => 'numeric|gte:0',
            'details.*.unit_cost' => 'numeric|gte:0',
        ];
    }
}
