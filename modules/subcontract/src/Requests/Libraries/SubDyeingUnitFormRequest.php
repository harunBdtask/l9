<?php

namespace SkylarkSoft\GoRMG\Subcontract\Requests\Libraries;

use Illuminate\Foundation\Http\FormRequest;

class SubDyeingUnitFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $id = $this->route('sub_dyeing_unit')->id ?? null;

        return [
            'name' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i"],
            'email' => "email|unique:sub_dyeing_units,email,{$id},id,deleted_at,NULL",
        ];
    }
}
