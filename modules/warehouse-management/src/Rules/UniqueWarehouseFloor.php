<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseFloor;

class UniqueWarehouseFloor implements Rule
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

        $warehouse_floor = WarehouseFloor::where('name', $value)
            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $warehouse_floor = $warehouse_floor->where('id', '!=', request()->route('id'));
        }

        $warehouse_floor = $warehouse_floor->first();

        return $warehouse_floor ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This :attribute already exits.';
    }
}
