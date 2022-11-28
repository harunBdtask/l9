<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\Libraries;

use Illuminate\Foundation\Http\FormRequest;

class SubDyeingOperationFunctionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'dyeing_recipe_operation_id' => 'required',
            'function_name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
        ];
    }
}
