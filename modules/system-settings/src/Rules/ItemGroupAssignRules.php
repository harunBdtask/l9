<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroupAssign as ItemGroupAssignModel;

class ItemGroupAssignRules implements Rule
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
        $item_id = request()->get('item_id');
        $group_id = request()->get('item_group_id');
        $factory_id = request()->get('factory_id');
        $item_group = ItemGroupAssignModel::where([
                    'item_id' => $item_id,
                    'factory_id' => $factory_id,
                    'item_group_id' => $group_id,
                ]);

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
        return 'This item is already added in this group';
    }
}
