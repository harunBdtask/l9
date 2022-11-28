<?php

namespace SkylarkSoft\GoRMG\Planing\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemCategoryFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required',
                Rule::unique('pln_item_categories', 'name')
                    ->ignore($this->route('id'))
                    ->whereNull('deleted_at'),
            ],
            'smv_from' => ['required', 'numeric'],
            'smv_to' => ['required', 'numeric'],
        ];
    }
}
