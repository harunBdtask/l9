<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeSalaryInfoRequest extends FormRequest
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
            'gross'             => 'nullable',
            'basic'             => 'nullable',
            'house_rent'        => 'nullable',
            'transport'         => 'nullable',
            'medical'           => 'nullable',
            'food'              => 'nullable',
            'out_of_city'       => 'nullable',
            'mobile_allowence'  => 'nullable',
            'attendance_bonus'  => 'nullable',
        ];
    }
}
