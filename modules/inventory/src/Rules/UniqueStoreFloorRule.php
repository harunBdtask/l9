<?php
namespace SkylarkSoft\GoRMG\Inventory\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\StoreFloor;

class UniqueStoreFloorRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void| boolean
     */
    public function __construct()
    {
        return true;
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

        $store_floor = StoreFloor::where([
            'name' => $value,
            'store_id' => request()->get('store_id'),
        ]);

        if (request()->get('id')) {
            $store_floor = $store_floor->where('id', '!=', request()->get('id'));
        }

        $store_floor = $store_floor->first();

        return $store_floor ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This floor already exits under the given store.';
    }
}
