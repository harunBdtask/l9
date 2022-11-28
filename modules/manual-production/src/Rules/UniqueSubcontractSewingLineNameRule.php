<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractSewingFloor;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractSewingLine;

class UniqueSubcontractSewingLineNameRule implements Rule
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
        $subcontract_sewing_floor_id = request()->get('subcontract_sewing_floor_id');
        $line = SubcontractSewingLine::where('line_name', $value)
            ->where('subcontract_sewing_floor_id', $subcontract_sewing_floor_id);

        if (request()->route('id')) {
            $line = $line->where('id', '!=', request()->route('id'));
        }

        $line = $line->first();
        $this->floor_name = SubcontractSewingFloor::find($subcontract_sewing_floor_id)->floor_name;

        return $line ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Duplicate sewing line found in $this->floor_name!";
    }
}
