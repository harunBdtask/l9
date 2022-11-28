<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewFabricCompositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'fabric_nature_id.required' => 'This Field is required.',
            'color_range_id.required' => 'This Field is required.',
            'construction.required' => 'This Field is required.',
//            'gsm.required' => 'This Field is required.',
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
            'fabric_nature_id' => 'required',
            'color_range_id' => 'required',
            'construction' => 'required',
            'status' => 'required',
            'percentage.*' => 'int|max:100',
            'percentage_total' => 'int|max:100',
            //'gsm' => 'required',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'percentage_total' => array_sum(request()->input('percentage') ?? []),
        ]);
    }
}
