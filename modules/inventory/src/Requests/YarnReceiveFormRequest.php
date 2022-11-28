<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YarnReceiveFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'source'           => 'required',
            'store_id'         => 'required',
            'factory_id'       => 'required',
            'challan_no'       => 'required',
            'receive_basis'    => 'required',
            'receive_purpose'  => 'required',
            'currency_id'      => 'required',
            'exchange_rate'    => 'required',
            'receive_date'     => 'required|date',
            'receive_basis_no' => [function ($attribute, $value, $fail) {
                return request('receive_basis') != "independent" && !$value ? $fail('required') : false;
            }],
        ];
    }

    public function messages(): array
    {
        return [
            "required" => "Required"
        ];
    }
}
