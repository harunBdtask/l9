<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MailGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('mail_groups', 'name')
                    ->ignore($this->route('mailGroup')),
            ],
            'users' => 'required|array'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
