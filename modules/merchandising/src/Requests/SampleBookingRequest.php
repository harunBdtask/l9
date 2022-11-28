<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SampleBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factory_id'          => 'required',
            'buyer_id'            => 'required',
            'supplier_id'         => 'required',
            'booking_date'        => 'required|date',
            'delivery_date'       => 'required|date',
            'currency_id'         => 'required',
            'pay_mode'            => 'nullable|numeric',
            'fabric_source'       => 'nullable|numeric',
            'exchange_rate'       => 'nullable|numeric',
            'internal_ref_no'     => 'nullable|string|max:40',
            'team_leader_id'      => 'nullable',
            'dealing_merchant_id' => 'nullable',
            'attention'           => 'nullable|string|max:255',
            'is_short'            => ['nullable'],
            'ready_to_approve'    => ['nullable']
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Required'
        ];
    }
}