<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniquePoColorSize implements Rule
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
        $colors = request()->get('color_id');
        $sizes = request()->get('size_id');
        $item_id_breakdown = request()->get('item_id_breakdown');
        $fabrication = request()->get('fabrication');
        $fabric_description = request()->get('fabric_description');
        $color_type = request()->get('color_type');
        $gsm = request()->get('gsm');
        foreach ($colors as $i => $color) {
            foreach ($colors as $j => $color) {
                if (($i != $j)
                    && ($colors[$i] == $colors[$j])
                    && ($sizes[$i] == $sizes[$j])
                    && ($item_id_breakdown[$i] == $item_id_breakdown[$j])
                    && ($fabrication[$i] == $fabrication[$j])
                    && ($fabric_description[$i] == $fabric_description[$j])
                    && ($color_type[$i] == $color_type[$j])
                    && ($gsm[$i] == $gsm[$j])
                ) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
