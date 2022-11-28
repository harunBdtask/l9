<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueLine;

class LineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return bool
     */
    public function messages()
    {
        return [
            'floor_id.required' => 'Floor no is required.',
            'line_no.required' => 'Line no is required.',
            'sort.integer' => 'Must be an integer.',
            'sort.min' => 'Negative value not allowed.',
            'sort.not_in' => 'Must be positive integer.',
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
            'floor_id' => ['required'],
            'line_no' => ['required',"not_regex:/([^\w\d\s&\-'])+/i", new UniqueLine()],
            'sort' => ['nullable', 'integer', 'min:0', 'not_in:0'],
        ];
    }
}
