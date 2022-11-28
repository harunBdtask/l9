<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ProductTypeRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function messages()
    {
        return [
            'name.required' => 'Product Type is Required',
        ];
    }

    public function rules()
    {
        return [
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|max:50|unique:product_types,name,". $this->segment(2),
        ];
    }
}
