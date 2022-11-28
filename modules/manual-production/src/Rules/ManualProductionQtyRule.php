<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Rules;

use Illuminate\Contracts\Validation\Rule;

class ManualProductionQtyRule implements Rule
{
    private $production_qty, $remaining_qty;
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
        $production_qty = request()->get('production_qty');
        if ($production_qty && is_array($production_qty) && count($production_qty)) {
            $attribute_split = explode('.', $attribute);
            $remaining_qty = request()->get('remaining_production_qty')[$attribute_split[1]];
        } else {
            $remaining_qty = request()->get('remaining_production_qty');
        }
        $this->production_qty = $value;
        $this->remaining_qty = $remaining_qty;
        return $value > $remaining_qty ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The production qty $this->production_qty cannot be greater than remaining qty  $this->remaining_qty.";
    }
}
