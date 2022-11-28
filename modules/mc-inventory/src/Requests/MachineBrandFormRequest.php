<?php

namespace SkylarkSoft\GoRMG\McInventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MachineBrandFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
