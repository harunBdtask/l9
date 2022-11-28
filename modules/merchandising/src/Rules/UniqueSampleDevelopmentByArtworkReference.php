<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Merchandising\Models\Sample;

class UniqueSampleDevelopmentByArtworkReference implements Rule
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
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strtoupper($value);

        $sample = Sample::where('artwork_ref_id', $value);
//            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $sample = $sample->where('id', '!=', request()->route('id'));
        }
        $sample = $sample->first();

        return $sample ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sample already exists with this reference';
    }
}
