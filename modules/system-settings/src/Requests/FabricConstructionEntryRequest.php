<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class FabricConstructionEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            'construction_name.required' => 'Construction Name is required.',
        ];
    }

    public function rules(): array
    {
        return [
            'construction_name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:fabric_construction_entries,construction_name," . $this->segment(2) . ',id',
            //'construction_name' => 'required',
        ];
    }
}
