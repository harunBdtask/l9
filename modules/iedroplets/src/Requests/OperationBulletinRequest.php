<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Iedroplets\Rules\UniqueOperationBulletin;

class OperationBulletinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check();
    }

    /**
     * Get the validation messages that apply to the erroneous request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'buyer_id.required' => 'Buyer selection is required.',
            'order_id.required' => 'Order is required.',
            'floor_id.required' => 'Floor is required.',
            'line_id.required' => 'Line date is required.',
            'proposed_target.required' => 'Proposed target date format.',
            'sketch' => 'Sketch is required.',
            'operator_skill_id.*.required' => 'Operator skill is required.',
            'task_id.*.required' => 'Task is required.',
            'machine_type_id.*.required' => 'Machine type is required.',
            'work_station.*.required' => 'Work station is required.',
            'time.*.required' => 'Time is required.',
            'idle_time.*.required' => 'Idle time is required.',
            'new_work_station.*.required' => 'New work station is required.',
            'new_time.*.required' => 'New time is required.',
            'new_idle_time.*.required' => 'New idle time is required.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'buyer_id' => 'required',
            'order_id' => ['required', new UniqueOperationBulletin],
            'floor_id' => 'required',
            'line_id' => 'required',
            'proposed_target' => 'required|numeric|integer|min:1',
            'operator_skill_id.*' => 'required',
            'task_id.*' => 'required|distinct',
            'machine_type_id.*' => ['required'],
            'work_station.*' => 'required|numeric|integer|min:1',
            'time.*' => 'required|numeric|numeric|integer|min:1',
            'idle_time.*' => 'required|numeric',
            'new_work_station.*' => 'required|numeric',
            'new_time.*' => 'required|numeric',
            'new_idle_time.*' => 'required|numeric',
        ];
    }
}
