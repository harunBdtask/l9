<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamAssignRequest extends FormRequest
{
    public function authorize()
    {
        return \Auth::check();
    }

    public function messages()
    {
        return [
            'team_id.required' => 'Team Name is required.',
            'member_id.*.unique' => 'user already assigned another team',
            'member_id.*.required' => 'Member is required',
            'member_id.*.distinct' => 'Duplicate value!! ',
        ];
    }

    public function rules()
    {
        return [
            'team_id' => 'required',
            'is_team_lead.*' => 'required',
            'member_id.*' => 'required|distinct',
        ];
    }
}
