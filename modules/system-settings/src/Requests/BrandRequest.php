<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueBrandName;

class BrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'brand_name.required' => 'This Field is required.',
            'brand_type.required' => 'This Field is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'brand_name' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", new UniqueBrandName()],
            'brand_type' => 'required',
        ];
    }
}