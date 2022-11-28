<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\HR\Rules\UniqueSectionOtApprovalRule;

class OtApprovalRequest extends FormRequest
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

    /**
     * Validation Messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ot_date.required' => 'OT Date is required.',
            'ot_date.date' => 'Must be a date.',
            'ot_start_time.required' => 'OT Start Time is required.',
            'ot_end_time.required' => 'OT End Time is required.',
            'ot_for.required' => 'OT For is required.',
            'ot_for.integer' => 'OT For is required.',
            'department_id.*.required' => 'Department is required.',
            'department_id.*.integer' => 'Department is required.',
            'section_id.*.required' => 'Section is required.',
            'section_id.*.integer' => 'Section is required.',
            'section_id.*.distinct' => 'Section must be distinct.',
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
            'ot_date' => ['required', 'date'],
            'ot_start_time' => 'required',
            'ot_end_time' => 'required',
            'ot_for' => 'required|integer',
            'department_id.*' => ['required','integer'],
            'section_id.*' => ['required','integer', 'distinct', new UniqueSectionOtApprovalRule],
            'file' => 'nullable|file|mimes:jpg,jpeg,bmp,png,pdf,xls,xlsx,doc,docx|max:5120',
            'approved_by' => 'nullable',
            'remarks' => 'nullable'
        ];
    }
}
