<?php

namespace SkylarkSoft\GoRMG\Planing\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Planing\Rules\ContainerNameRule;

class ContainerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'factory_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'details' => 'required',
            'details.*.container_no' => new ContainerNameRule(),
        ];
    }
}
