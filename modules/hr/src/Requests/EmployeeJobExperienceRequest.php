<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeJobExperienceRequest extends FormRequest
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
            'employee_job_experiences'                      => 'required|array',
            'employee_job_experiences.*.company_name'       => 'nullable',
            'employee_job_experiences.*.ex_job_designation' => 'nullable',
            'employee_job_experiences.*.from_date'          => 'nullable',
            'employee_job_experiences.*.to_date'            => 'nullable',
            'employee_job_experiences.*.ex_job_salary'      => 'nullable',
            'employee_job_experiences.*.leave_reason'       => 'nullable',
        ];
    }
}
