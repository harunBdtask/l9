<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueColorOfOrder implements Rule
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
        $orders = request()->get('order_id');
        $fabric_types = request()->get('fabric_type');
        $composition_fabrics = request()->get('composition_fabric_id');
        $colors = request()->get('color_id');

        foreach ($orders as $i => $order) {
            //$colorArr = [];
            foreach ($colors as $j => $color) {
                /*if ($order == $orders[$j] && $order == $orders[$j] && $order == $orders[$j]) {
                    $colorArr[] = $color;
                }
                if (count($colorArr) != count(array_unique($colorArr))) {
                    return false;
                }*/
                if (($i != $j) && ($orders[$i] == $orders[$j]) && ($fabric_types[$i] == $fabric_types[$j]) && ($composition_fabrics[$i] == $composition_fabrics[$j])) {
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
        return 'Color duplication';
    }
}
