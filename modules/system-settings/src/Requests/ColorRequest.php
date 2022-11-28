<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Color name is required.',
            'status.required' => 'Status is required.',
            'name.unique' => 'Color name already been taken for this type.',
        ];
    }

    public function rules(): array
    {
        return [
//            'name' => 'required|unique:colors,name,' . $this->segment(2),
            'name' => [
                'required',
                Rule::unique('colors')
                    ->where('status', $this->status)
                    ->ignore(request()->route('id')),
            ],
            'status' => 'required'
        ];
    }
}
