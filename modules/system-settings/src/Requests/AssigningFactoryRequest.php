<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AssigningFactoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Assigning Factory name is required.'
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|unique:assigning_factories,name,' . $this->segment(2) . ',id,deleted_at,NULL',
        ];
    }
}
