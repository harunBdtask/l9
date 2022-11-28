<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Knitting\Rules\KnitProgramFabricColorQtyRule;

class KnitProgramFabricColorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'item_color_id.*' => 'required',
            'item_color.*' => 'required',
            'booking_qty.*' => 'required|numeric',
            'program_qty.*' => ['required','numeric', new KnitProgramFabricColorQtyRule],
            'max_program_qty.*' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Field is required',
            'numeric' => 'Field must be a number'
        ];
    }
}
