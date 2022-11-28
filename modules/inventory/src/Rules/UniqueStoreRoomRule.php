<?php
namespace SkylarkSoft\GoRMG\Inventory\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\StoreRoom;

class UniqueStoreRoomRule implements Rule
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

        $store_room = StoreRoom::where([
            'name' => $value,
            'floor_id' => request()->get('floor_id'),
        ]);

        if (request()->get('id')) {
            $store_room = $store_room->where('id', '!=', request()->get('id'));
        }

        $store_room = $store_room->first();

        return $store_room ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This room already exits under the given floor.';
    }
}
