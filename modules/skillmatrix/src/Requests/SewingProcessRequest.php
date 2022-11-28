<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Requests;

use App\Constants\ApplicationConstant;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Skillmatrix\Rules\UniqueSewingProcess;

class SewingProcessRequest extends FormRequest
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
    public function messages(): array
    {
        return [
            'name.required' => 'This process name is required.',
            'standard_capacity.required' => 'Standard capacity is required.',
            'standard_capacity.numeric' => 'Standard capacity must be numeric.',
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
            'name' => [
                'required',
                'max:50',
                "not_regex:" . ApplicationConstant::SYMBOL_FILTER_REGEX,
                new UniqueSewingProcess(),
            ],
            'standard_capacity' => 'required|numeric',
        ];
    }
}
