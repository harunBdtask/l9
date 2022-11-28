<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MailSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mail_type' => [
                'required',
                Rule::unique('mail_settings', 'mail_type')
                    ->ignore($this->route('mailSetting'))
            ],
            'receiver_groups' => 'required|array'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
