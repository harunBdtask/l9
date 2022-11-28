<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryHistoriesRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'salary_histories.*.id' => 'nullable',
            'salary_histories.*.employee_id' => 'required',
            'salary_histories.*.designation_id' => 'required',
            'salary_histories.*.department_id' => 'required',
            'salary_histories.*.year' => 'required',
            'salary_histories.*.gross_salary' => 'required',
        ];
    }
}
