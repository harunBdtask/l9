<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Rules\UniqueLeave;

class LeaveSettingRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'employee_type' => 'required',
            'leave_type_id' => 'required',
            'number_of_days' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'employee_type.required' => 'The Employee type field is required',
            'leave_type_id.required' => 'The Leave type field is required',
            'number_of_days.required' => 'The Number of Days field is required',
        ];
    }

}
