<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('notification_groups', 'name')
                    ->ignore($this->route('notificationGroup')),
            ],
            'users' => 'required|array'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
