<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductCateory;

class UniqueProductCategory implements Rule
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

        $productCategory = ProductCateory::where('category_name', $value)
           ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $productCategory = $productCategory->where('id', '!=', request()->route('id'));
        }

        $productCategory = $productCategory->first();

        return $productCategory ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Product category has duplicate entry.';
    }
}
