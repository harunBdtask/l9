<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;

class UniqueProductDepartment implements Rule
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

        $product_department = ProductDepartments::where('product_department', $value)
            ->where('factory_id', \Auth::user()->factory_id)->where('is_deleted', '!=', 1);

        if (request()->route('id')) {
            $product_department = $product_department->where('id', '!=', request()->route('id'));
        }

        $product_department = $product_department->first();

        return $product_department ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This product department already exits.';
    }
}
