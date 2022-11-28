<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Requests;

use App\Constants\ApplicationConstant;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Skillmatrix\Rules\UniqueSewingMachine;

class SewingMachineRequest extends FormRequest
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
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Sewing machine name is required.',
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
                new UniqueSewingMachine(),
            ],
        ];
    }
}
