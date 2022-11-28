<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RollWiseFabricDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'challan_no'  => 'required',
            'company_id' => 'required',
            'booking_company_id'  => 'required',
            'buyer_id'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Field is required'
        ];
    }
}
