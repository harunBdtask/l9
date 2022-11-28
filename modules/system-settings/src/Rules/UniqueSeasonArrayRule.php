<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class UniqueSeasonArrayRule implements Rule
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
        $attributes = \explode('.', $attribute);
        $key = $attributes[1];
        $seasons = request()->get('season_name');
        $yearFroms = request()->get('year_from');
        $yearTos = request()->get('year_to');
        $validation = true;
        foreach ($seasons as $s_key => $s_val) {
            if ($key == $s_key) {
                continue;
            }
            if ($seasons[$key] == $seasons[$s_key] && $yearFroms[$key] == $yearFroms[$s_key] && $yearTos[$key] == $yearTos[$s_key]) {
                $validation = false;
                break;
            }
        }
        return $validation;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Same combination not allowed.';
    }
}
