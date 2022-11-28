<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WashingChallanUpdateRequest extends FormRequest
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
     * @return bool|array
     */
    public function messages()
    {
        return [
            'print_wash_factory_id.required' => 'This is required.',
            'bag.required' => 'This Field is required.',
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
            'print_wash_factory_id' => 'required',
            'bag' => 'required',
        ];
    }
}
