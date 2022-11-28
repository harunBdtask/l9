<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CommercialCostMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            'method.required' => 'Method name is required.',
            'percentage.required' => 'Percentage is required.',
            'writeable.required' => 'writeable is required.',
        ];
    }

    public function rules(): array
    {
        return [
            'method' => ['required'],
            'percentage' => 'required|numeric|max:100|min:0',
            'writeable' => 'required',
        ];
    }
}
