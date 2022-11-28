<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeMonthlyAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'department_id' => 'required',
            'section_id' => 'required',
            'month' => 'required',
            'year' => 'required',
        ];
    }

}
