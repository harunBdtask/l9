<?php

namespace SkylarkSoft\GoRMG\McInventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MachineLocationFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'location_name' => 'required',
            'location_type' => 'required'
        ];
    }
}
