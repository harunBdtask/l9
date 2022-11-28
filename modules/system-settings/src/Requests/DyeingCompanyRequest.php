<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class DyeingCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
        ];
    }
}
