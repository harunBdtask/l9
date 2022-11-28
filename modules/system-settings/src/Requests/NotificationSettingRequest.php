<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'notification_type' => [
                'required',
                Rule::unique('notification_settings', 'notification_type')
                    ->ignore($this->route('notificationSetting'))
            ],
            'receiver_groups' => 'required|array'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
