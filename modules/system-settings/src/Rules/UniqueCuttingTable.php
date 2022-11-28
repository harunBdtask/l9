<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;

class UniqueCuttingTable implements Rule
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
        $floorId = request()->get('cutting_floor_id');
        $cutting_table = CuttingTable::where('table_no', $value)
            ->where('cutting_floor_id', $floorId)
            ->where('factory_id', Auth::user()->factory_id);

        if (request()->route('id')) {
            $cutting_table = $cutting_table->where('id', '!=', request()->route('id'));
        }

        $cutting_table = $cutting_table->first();

        return $cutting_table ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This cutting table no already exists in this floor.';
    }
}
