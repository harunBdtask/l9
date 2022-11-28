<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class UniqueUOM implements Rule
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

        $uom = UnitOfMeasurement::where('unit_of_measurements', $value)
            ->where('factory_id', \Auth::user()->factory_id)->where('is_deleted', '!=', 1);

        if (request()->route('id')) {
            $uom = $uom->where('id', '!=', request()->route('id'));
        }

        $uom = $uom->first();

        return $uom ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This name for UOM already exits.';
    }
}
