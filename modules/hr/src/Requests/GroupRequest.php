<?php

namespace SkylarkSoft\GoRMG\HR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "name" => [
                "required",
                Rule::unique('hr_groups', 'name')->ignore($this->route('hrGroup'))
            ],
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
