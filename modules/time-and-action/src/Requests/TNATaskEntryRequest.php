<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SkylarkSoft\GoRMG\TimeAndAction\Rules\ConnectedTaskRule;

class TNATaskEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'numeric'],
            'user_id' => ['required'],
            'task_name' => ['required'],
            'task_short_name' => ['required', 'max:20'],
            'group_id' => ['required', 'numeric'],
            'group_sequence' => ['required', 'numeric'],
            'status' => ['required', 'numeric'],
            'sequence' => ['required', 'numeric'],
            'connected_task_id' => request()->get('plan_date_is_editable') == 0
                ? ['required', 'gt:0', new ConnectedTaskRule()]
                : 'sometimes',
            'lead_time_wise_days.*.days' => request()->get('plan_date_is_editable') == 0 ? ['required', 'numeric'] : 'sometimes',
            'lead_time_wise_days.*.lead_time' => request()->get('plan_date_is_editable') == 0 ? ['required', 'numeric'] : 'sometimes',
        ];
    }

    public function messages(): array
    {
        return [
            //'connected_task_id' => 'Field is required'
        ];
    }
}
