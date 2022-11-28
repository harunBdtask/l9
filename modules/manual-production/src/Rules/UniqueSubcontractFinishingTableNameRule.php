<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFinishingFloor;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFinishingTable;

class UniqueSubcontractFinishingTableNameRule implements Rule
{
    private $floor_name;
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
        $subcontract_finishing_floor_id = request()->get('subcontract_finishing_floor_id');
        $floor = SubcontractFinishingTable::where('table_name', $value)
            ->where('subcontract_finishing_floor_id', $subcontract_finishing_floor_id);

        if (request()->route('id')) {
            $floor = $floor->where('id', '!=', request()->route('id'));
        }

        $floor = $floor->first();
        $this->floor_name = SubcontractFinishingFloor::find($subcontract_finishing_floor_id)->floor_name;

        return $floor ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Duplicate finishing table found in $this->floor_name!";
    }
}
