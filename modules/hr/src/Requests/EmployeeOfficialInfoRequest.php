<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\HR\Rules\UniqueEmployeeId;
use SkylarkSoft\GoRMG\HR\Rules\UniquePunchCard;

class EmployeeOfficialInfoRequest extends FormRequest
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
        $rules = [
            'department_id'   => 'required',
            'designation_id'  => 'required',
            'section_id'      => 'required',
            'grade_id'        => 'required',
            'type'            => 'required',
            'code'            => 'required',
            'unique_id'       => ['required', new UniqueEmployeeId()],
            'punch_card_id'   => ['nullable', new UniquePunchCard()],
            'date_of_joining' => 'required',
            'bgmea_id'        => 'required',
        ];

        if ($this->input('shift_enabled') === 'yes') {
            $rules['shift_id'] = 'required';
        }

        if ($this->input('bank_id')) {
            $rules['account_no'] = 'required';
        }

        return $rules;
    }
}
