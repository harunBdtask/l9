<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\HR\Rules\ManualAttendanceEntryDateRule;
use SkylarkSoft\GoRMG\HR\Rules\ManualAttendanceEntryRule;

class ManualAttendanceRequest extends FormRequest
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
            'attendance_date.required' => 'Attendance date is required.',
            'attendance_date.date' => 'Attendance date is must be a date.',
            'employee_id.required' => 'Employee is required.',
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
            'attendance_date' => ['required', 'date', new ManualAttendanceEntryDateRule],
            'employee_id' => 'required|numeric',
            'in_time' => [new ManualAttendanceEntryRule],
            'out_time' => [new ManualAttendanceEntryRule],
            'lunch_start' => [new ManualAttendanceEntryRule],
            'lunch_end' => [new ManualAttendanceEntryRule],
        ];
    }
}
