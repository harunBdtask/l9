<?php
namespace SkylarkSoft\GoRMG\Inventory\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\StoreShelf;

class UniqueStoreShelfRule implements Rule
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

        $store_shelf = StoreShelf::where([
            'name' => $value,
            'rack_id' => request()->get('rack_id'),
        ]);

        if (request()->get('id')) {
            $store_shelf = $store_shelf->where('id', '!=', request()->get('id'));
        }

        $store_shelf = $store_shelf->first();

        return $store_shelf ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This shelf already exits under the given rack.';
    }
}
