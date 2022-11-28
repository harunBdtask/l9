<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueSampleDevelopment implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        $colors = request()->get('color_id');
        $sizes = request()->get('size_id');
        $countries = request()->get('country_id');

        foreach ($colors as $i => $color) {
            foreach ($colors as $j => $color) {
                if (($i != $j) && ($colors[$i] == $colors[$j]) && ($sizes[$i] == $sizes[$j])) {
                    return false;
                }
            }
        }

        return true;
    }

    public function message()
    {
        return 'Invalid Color Size Breakdown';
    }
}
