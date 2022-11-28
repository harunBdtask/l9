<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DyeingProductionFormRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'dyeing_order_id' => 'required',
            'dyeing_batch_id' => 'required',
            'production_date' => 'required',
            'dyeing_production_details' => 'required',
            'dyeing_production_details.*.no_of_roll' => 'required|numeric|gte:0',
            'dyeing_production_details.*.dyeing_production_qty' => 'required|numeric|gte:0',
            'dyeing_production_details.*.unit_cost' => 'required|numeric|gte:0',
        ];
    }

}
