<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueProductDepartment;

class ProductDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return bool
     */
    public function messages()
    {
        return [
            'product_department.required' => 'Product Department is required.',
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
            'product_department' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", new UniqueProductDepartment],
//            'product_department' => ['required'],
        ];
    }
}
