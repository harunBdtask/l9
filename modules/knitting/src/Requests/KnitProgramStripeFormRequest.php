<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KnitProgramStripeFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'body_part' => 'required',
            'fabric_description' => 'required',
            'fabric_nature_id' => 'required',
            'fabric_nature' => 'required',
            'item_color_id' => 'required',
            'stripe_details' => 'required', //new
//            'stripe_details.*.stripe_color' => 'required',
//            'stripe_details.*.measurement' => 'required',
//            'stripe_details.*.uom_id' => 'required',
//            'stripe_details.*.total_feeder' => 'required',
        ];
    }
}
