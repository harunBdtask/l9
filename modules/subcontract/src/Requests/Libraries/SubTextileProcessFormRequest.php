<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\Libraries;

use Illuminate\Foundation\Http\FormRequest;

class SubTextileProcessFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
            'factory_id' => 'required',
            'sub_textile_operation_id' => 'required',
        ];
    }
}
