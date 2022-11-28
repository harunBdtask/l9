<?php
namespace SkylarkSoft\GoRMG\Inventory\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRack;

class UniqueStoreRackRule implements Rule
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

        $store_rack = StoreRack::where([
            'name' => $value,
            'room_id' => request()->get('room_id'),
        ]);

        if (request()->get('id')) {
            $store_rack = $store_rack->where('id', '!=', request()->get('id'));
        }

        $store_rack = $store_rack->first();

        return $store_rack ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This rack already exits under the given room.';
    }
}
