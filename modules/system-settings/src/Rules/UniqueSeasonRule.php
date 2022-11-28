<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class UniqueSeasonRule implements Rule
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
        $value = strtoupper($value);

        $data = Season::query()
            ->where('season_name', $value)
            ->where('buyer_id',request()->get('buyer_id'))
            ->where('year_from',request()->get('year_from'))
            ->where('year_to',request()->get('year_to'))
            ->where('factory_id',request()->get('factory_id'));

        if (request()->route('id')) {
            $data = $data->where('id', '!=', request()->route('id'));
        }

        $data = $data->first();

        return $data ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This season already exits under the given buyer and year range.';
    }
}
