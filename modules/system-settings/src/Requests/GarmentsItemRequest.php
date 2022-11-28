<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class GarmentsItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Garments Item name is required',
            'commercial_name.required' => 'Commercial name is required',
            'product_category_id.required' => 'Product Category is required',
            'product_type.required' => 'Product Type is required',
        ];
    }

    public function rules(): array
    {
        return [
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|max:50|unique:garments_items,name," . $this->segment(2).',id',
            'commercial_name' => "required|max:50|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
            'product_category_id' => 'required|numeric',
            'product_type' => 'required',
        ];
    }
}
