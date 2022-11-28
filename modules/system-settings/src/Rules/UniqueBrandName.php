<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Brand;

class UniqueBrandName implements Rule
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

        $brand = Brand::where([
            'brand_name' => $value,
            'brand_type' => request()->get('brand_type'),
        ]);

        if (request()->route('id')) {
            $brand = $brand->where('id', '!=', request()->route('id'));
        }

        $brand = $brand->first();

        return $brand ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This brand already exits.';
    }
}
