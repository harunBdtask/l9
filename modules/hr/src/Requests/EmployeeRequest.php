<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name'            => 'required',
            'nid'                   => 'required',
            'date_of_birth'         => 'required',
            'father_name'           => 'required',
            'mother_name'           => 'required',
            'marital_status'        => 'required',
            'present_address'       => 'required',
            'permanent_address'     => 'required',
            'physical_appearance'   => 'required',
            'sex'                   => 'required',
            'religion'              => 'required',
        ];
    }
}
