<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeEducationInfoRequest extends FormRequest
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
            'employee_educations' => 'required|array',
            'employee_educations.*.degree' => 'nullable',
            'employee_educations.*.institution' => 'nullable',
            'employee_educations.*.board' => 'nullable',
            'employee_educations.*.result' => 'nullable',
            'employee_educations.*.year' => 'nullable',
        ];
    }
}
