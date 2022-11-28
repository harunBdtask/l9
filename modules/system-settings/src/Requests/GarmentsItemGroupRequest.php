<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class GarmentsItemGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Garments Item Group name is required',
        ];
    }

    public function rules(): array
    {
        return [
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|max:255|unique:garments_items,name," . $this->segment(2).',id',
        ];
    }
}
