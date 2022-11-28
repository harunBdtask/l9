<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Requests;

use App\Constants\ApplicationConstant;
use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Skillmatrix\Rules\UniqueSewingOperatorId;

class SewingOperatorRequest extends FormRequest
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
            'name.required' => 'Sewing operator name is required.',
            'floor_id.required' => 'Floor no is required.',
            'line_id.required' => 'Line no is required.',
            'image.required' => 'Photo must be jpeg,png,jpg,gif and less than or equal 1024 required.',
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
            'name' => ['required', 'max:40', "not_regex:" . ApplicationConstant::SYMBOL_FILTER_REGEX],
            'title' => ['required', 'max:40', "not_regex:" . ApplicationConstant::SYMBOL_FILTER_REGEX],
            'operator_grade' => ['required', 'max:20', "not_regex:" . ApplicationConstant::SYMBOL_FILTER_REGEX],
            'floor_id' => 'required|integer',
            'line_id' => 'required|integer',
            'present_salary' => 'required|numeric',
            'joinning_date' => 'required|date',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif|max:1024',
            'operator_id' => [
                'required',
                'max:40',
                new UniqueSewingOperatorId(),
            ],
        ];
    }
}
