<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\HR\Rules\UniqueFestivalLeaveDateRule;

class FestivalLeaveRequest extends FormRequest
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
            'name.required' => 'Festival Name is required.',
            'name.max' => 'Maximum 300 characters exceeded.',
            'leave_date.required' => 'Leave Date is required.',
            'leave_date.date' => 'Leave Date must be a date.',
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
            'name' => 'required|max:300',
            'leave_date' => ['required', 'date', new UniqueFestivalLeaveDateRule],
        ];
    }
}
