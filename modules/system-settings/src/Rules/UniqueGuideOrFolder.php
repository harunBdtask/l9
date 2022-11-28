<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\GuideOrFolder;

class UniqueGuideOrFolder implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return bool
     */
    public function __construct()
    {
        return Auth::check();
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

        $guideFolder = GuideOrFolder::where('name', $value)
            ->where('factory_id', Auth::user()->factory_id);

        if (request()->route('id')) {
            $guideFolder = $guideFolder->where('id', '!=', request()->route('id'));
        }

        $guideFolder = $guideFolder->first();

        return $guideFolder ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This name for guide folder already exits.';
    }
}
