<?php
namespace SkylarkSoft\GoRMG\Inventory\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\StoreBin;

class UniqueStoreBinRule implements Rule
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

        $store_bin = StoreBin::where([
            'name' => $value,
            'shelf_id' => request()->get('shelf_id'),
        ]);

        if (request()->get('id')) {
            $store_bin = $store_bin->where('id', '!=', request()->get('id'));
        }

        $store_bin = $store_bin->first();

        return $store_bin ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This bin already exits under the given shelf.';
    }
}
