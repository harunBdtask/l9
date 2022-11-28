<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DryerFormRequest extends FormRequest
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
            'dryer_details' => 'required',
            'dryer_details.*.fin_no_of_roll' => 'required|numeric|gte:0',
            'dryer_details.*.finish_qty' => 'required|numeric|gte:0',
            'dryer_details.*.unit_cost' => 'required|numeric|gte:0',
        ];
    }
}