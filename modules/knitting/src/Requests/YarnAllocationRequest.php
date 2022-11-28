<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YarnAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id.*'  => 'required',
            'buyer_id.*' => 'required',
            'booking_id.*'  => 'required',
            'booking_no.*'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Field is required'
        ];
    }
}
