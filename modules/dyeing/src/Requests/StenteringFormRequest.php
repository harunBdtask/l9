<?php

namespace SkylarkSoft\GoRMG\Dyeing\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StenteringFormRequest extends FormRequest
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
            'stentering_details' => 'required',
            'stentering_details.*.fin_no_of_roll' => 'required|numeric|gte:0',
            'stentering_details.*.finish_qty' => 'required|numeric|gte:0',
            'stentering_details.*.unit_cost' => 'required|numeric|gte:0',
        ];
    }
}