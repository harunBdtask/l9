<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\TeamMemberAssign;

class UniqueTeamID implements Rule
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

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $team = TeamMemberAssign::where('team_id', $value)->where('is_team_lead', 1)
            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $team = $team->where('id', '!=', request()->route('id'));
        }

        $team = $team->first();

        return $team ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This team already exits.';
    }
}
