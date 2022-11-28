<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\ManualProduction\Rules\UniqueSubcontractFactoryNameRule;

class SubcontractFactoryProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages()
    {
        return [
            'required' => 'Required field',
            'integer' => 'Integer field',
            'min' => 'Negative value not allowed',
        ];
    }

    public function rules(): array
    {
        return [
            'operation_type' => 'required|integer',
            'name' => ['required', new UniqueSubcontractFactoryNameRule()],
            'address' => 'required',
            'responsible_person' => 'required',
            'contact_no' => 'required',
        ];
    }
}
