<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function messages(): array
    {
        return [
            'required' => 'Required'
        ];
    }

    public function rules(): array
    {
        return [
            'team_name'    => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:teams,team_name,". $this->segment(2).',id',
            'short_name'   => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
            'project_type' => 'required',
            'status'       => 'required',
            'member_id.*'  => 'required',
            'role.*'       => 'required'
        ];
    }
}
