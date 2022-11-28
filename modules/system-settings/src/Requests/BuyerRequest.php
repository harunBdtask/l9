<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class BuyerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            'factory_id.required' => 'Company Is Required',
            'name.required' => 'Buyer name is required.',
            'short_name.required' => 'Short Name Is Required',
            'party_type.*.required' => 'Party Is Required',
            'party_type.*' => 'Party Type Is Required',
        ];
    }

    public function rules(): array
    {
        $uniqRule = Rule::unique('buyers')
            ->where('factory_id', $this->factory_id)
            ->ignore($this->segment(2))
            ->whereNull('deleted_at');

        //"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
        // |not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i
        return [
            'name' => ['required', $uniqRule],
            'short_name' => "required",
            'party_type.*' => 'required',
            'factory_id' => 'required',
        ];
    }
}
