<?php

namespace SkylarkSoft\GoRMG\McInventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MachineTypeFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'machine_category' => 'required',
            'machine_type' => 'required|alpha_dash',
        ];
    }
}
