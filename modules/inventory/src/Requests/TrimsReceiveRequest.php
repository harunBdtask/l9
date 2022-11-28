<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrimsReceiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receive_basic' => 'required',
            'factory_id' => 'required',
            'store_id' => 'required',
            'receive_date' => 'required|date',
            'challan_no' => 'required',
            'supplier_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'The factory name field is required.',
            'store_id.required' => 'The store name field is required.',
            'supplier_id.required' => 'The supplier name field is required.',
        ];
    }
}
