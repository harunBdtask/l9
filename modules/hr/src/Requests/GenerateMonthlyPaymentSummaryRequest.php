<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateMonthlyPaymentSummaryRequest extends FormRequest
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

    public function messages()
    {
        return [
            'month.required' => 'Month is required',
            'year.required' => 'Year is required',
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
            'month' => 'required',
            'year' => 'required',
            'unique_id' => 'nullable',
        ];
    }
}
