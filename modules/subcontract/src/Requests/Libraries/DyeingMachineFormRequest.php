<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\Libraries;

use Illuminate\Foundation\Http\FormRequest;

class DyeingMachineFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'floor_type' => 'required',
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
            'heating_rate' => 'required',
            'maximum_working_pressure' => 'required',
            'type' => 'required',
            'description' => 'required',
            'cooling_rate' => 'required',
            'maximum_working_temp' => 'required',
            'capacity' => 'required',
            'status' => 'required',
        ];
    }
}
