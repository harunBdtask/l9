<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\TeamMemberAssign;

class UniqueTeamMember implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        $team_member = TeamMemberAssign::where('member_id', $value)->where('factory_id', \Auth::user()->factory_id)->where('team_id', '!=', request()->team_id);
        if (request()->route('id')) {
            $team = $team_member->where('id', '!=', request()->route('id'));
        }
        $team_member = $team_member->first();

        return $team_member ? false : true;
    }

    public function message()
    {
        return 'User Already Assigned Into Another Team'.request()->team_id;
    }
}
