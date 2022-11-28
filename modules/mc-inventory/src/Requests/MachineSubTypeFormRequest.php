<?php

namespace SkylarkSoft\GoRMG\McInventory\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MachineSubTypeFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'machine_category' => 'required',
            'machine_type' => 'required',
            'machine_sub_type' => 'required'
        ];
    }
}
