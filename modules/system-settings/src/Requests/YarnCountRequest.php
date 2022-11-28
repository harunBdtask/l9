<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class YarnCountRequest extends FormRequest
{
    public function authorize() : bool
    {
        return Auth::check();
    }

    public function messages() : array
    {
        return [
            'yarn_count.required' => 'Yarn Count is required.',
        ];
    }

    public function rules() : array
    {
        return [
            'yarn_count' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:yarn_counts,yarn_count," . $this->segment(2).',id',
        ];
    }
}
