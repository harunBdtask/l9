<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;

class ItemGroupRule implements Rule
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
        $factory_id = request()->get('factory_id');

        $item_group = ItemGroup::where('item_group', $value)->where('factory_id', $factory_id);

        if (request()->route('id')) {
            $item_group = $item_group->where('id', '!=', request()->route('id'));
        }

        $item_group = $item_group->first();

        return $item_group ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Item Group Already Exists.';
    }
}
