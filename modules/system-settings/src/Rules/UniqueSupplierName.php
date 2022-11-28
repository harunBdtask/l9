<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;

class UniqueSupplierName implements Rule
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

        $supplier = Supplier::where('supplier_name', $value)
            ->where('factory_id', \Auth::user()->factory_id);

        if (request()->route('id')) {
            $supplier = $supplier->where('id', '!=', request()->route('id'));
        }

        $supplier = $supplier->first();

        return $supplier ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This supplier name already exits.';
    }
}
