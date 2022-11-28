<?php

namespace SkylarkSoft\GoRMG\TQM\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\TQM\Rules\UniqueTqmDefectRule;

class TqmDefectRequest extends FormRequest
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
     * @return bool|array
     */
    public function messages()
    {
        return [
            'required' => 'This field is required.',
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
            'factory_id' => 'required',
            'section' => 'required',
            'name' => ['required',"not_regex:/([^\w\d\s&'.\-\)\(\/])+/i", new UniqueTqmDefectRule],
        ];
    }
}
