<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;

class UniqueSeasonsRule implements Rule
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
        $value = strtoupper($value);
        $key = $attributes[1];
        $ids = request()->get('id');
        
        $data = Season::query()
            ->where('season_name', $value)
            ->where('buyer_id',request()->get('buyer_id'))
            ->where('year_from',request()->get('year_from')[$key])
            ->where('year_to',request()->get('year_to')[$key])
            ->where('factory_id',request()->get('factory_id'))
            ->when(($ids && \is_array($ids) && count($ids)), function($query) use($ids, $key){
                $query->where('id', '!=', $ids[$key]);
            })
            ->first();

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
