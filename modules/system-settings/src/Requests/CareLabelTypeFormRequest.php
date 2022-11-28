<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CareLabelTypeFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                Rule::unique('care_label_types', 'name')
                    ->ignore($this->route('careLabelType'))
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
