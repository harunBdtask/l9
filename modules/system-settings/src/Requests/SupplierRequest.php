<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Supplier Name is required.',
            'short_name.required' => 'Supplier Short Name is required.',
            'party_type.*.required' => 'Party Types is required.',
            'buyer_id.*.required' => 'Buyer is required.',
            'factory_id.required' => 'Tag Factory is required.',
        ];
    }

    public function rules(): array
    {
        return [
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:suppliers,name," . $this->segment(2) . ',id,deleted_at,NULL',
            'short_name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
            'party_type' => 'required',
            'buyer_id' => 'required',
            'factory_id' => 'required',
        ];
    }
}
