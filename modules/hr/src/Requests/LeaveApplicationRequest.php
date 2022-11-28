<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Rules\LeaveApplicationDateValidationRule;

class LeaveApplicationRequest extends FormRequest
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
            "type"             => "required",
            "department_id"    => 'required',
            "designation_id"   => 'required',
            "section_id"       => 'required',
            "employee_id"      => ['required', new LeaveApplicationDateValidationRule],
            "applicant_name"   => 'nullable',
            "reason"           => 'nullable',
            "application_date" => 'required|date|date_format:Y-m-d',
            "leave_start"      => ['required', 'date', 'date_format:Y-m-d'],
            "leave_end"        => ['required', 'date', 'date_format:Y-m-d'],
            "rejoin_date"      => 'required|date|date_format:Y-m-d',
            "contact_details"  => 'nullable',
            "application_for"  => ['required', Rule::in(['in_advance', 'leave_of_absence'])],
            "is_approved"      => ['required', Rule::in(['yes', 'no'])],
            "code"             => 'nullable',
        ];
    }
}
