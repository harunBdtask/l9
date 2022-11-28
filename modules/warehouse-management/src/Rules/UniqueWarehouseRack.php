<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseRack;

class UniqueWarehouseRack implements Rule
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

        $warehouse_floor_id = request()->get('warehouse_floor_id');
        $warehouse_rack = WarehouseRack::where('name', $value)
            ->where([
                'warehouse_floor_id' => $warehouse_floor_id,
                'factory_id' => \Auth::user()->factory_id,
            ]);

        if (request()->route('id')) {
            $warehouse_rack = $warehouse_rack->where('id', '!=', request()->route('id'));
        }

        $warehouse_rack = $warehouse_rack->first();

        return $warehouse_rack ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This rack already exits.';
    }
}
