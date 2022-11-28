<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Iedroplets\Rules\UniqueNextSchedule;

class NextScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'floor_id.*.required' => 'Floor selection is required.',
            'line_id.*.required' => 'Line selection is required.',
            'buyer_id.*.required' => 'Buyer selection is required.',
            'style_id.*.required' => 'Style selection is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'floor_id.*' => 'required',
            'line_id.*' => 'required',
            'buyer_id.*' => 'required',
            'style_id.*' => 'required',
        ];
    }
}
