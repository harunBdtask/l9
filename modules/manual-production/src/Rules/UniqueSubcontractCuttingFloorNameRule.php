<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractCuttingFloor;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile;

class UniqueSubcontractCuttingFloorNameRule implements Rule
{
    private $factory_name;
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
        $subcontract_factory_profile_id = request()->get('subcontract_factory_profile_id');
        $floor = SubcontractCuttingFloor::where('floor_name', $value)
            ->where('subcontract_factory_profile_id', $subcontract_factory_profile_id);

        if (request()->route('id')) {
            $floor = $floor->where('id', '!=', request()->route('id'));
        }

        $floor = $floor->first();
        $this->factory_name = SubcontractFactoryProfile::find($subcontract_factory_profile_id)->name;

        return $floor ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Duplicate cutting floor found in $this->factory_name!";
    }
}
