<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\Libraries;

use Illuminate\Foundation\Http\FormRequest;

class DyeingFloorFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'type' => 'required',
            'name' => 'required',
            'attention' => 'required',
        ];
    }
}
