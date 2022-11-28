<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DyeingFinishingProductionRequest extends FormRequest
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
            'production_date' => 'required',
            'finishing_production_details' => 'required',
            'finishing_production_details.*.no_of_roll' => 'required|numeric|gte:0',
            'finishing_production_details.*.finish_qty' => 'required|numeric|gte:0',
            'finishing_production_details.*.unit_cost' => 'required|numeric|gte:0',
        ];
    }
}