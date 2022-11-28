<?php

namespace SkylarkSoft\GoRMG\TQM\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinishingDhuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return array
     */
    public function messages(): array
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
    public function rules(): array
    {
        return [
            'factory_id.*' => 'required',
            'production_date.*' => 'required',
            'finishing_floor_id.*' => 'required',
        ];
    }
}
