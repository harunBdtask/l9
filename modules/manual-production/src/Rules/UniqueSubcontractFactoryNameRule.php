<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile;

class UniqueSubcontractFactoryNameRule implements Rule
{
    private $factory_type;
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
        $operation_type = request()->get('operation_type');
        $factory = SubcontractFactoryProfile::where('name', $value)
            ->where('operation_type', $operation_type);

        if (request()->route('id')) {
            $factory = $factory->where('id', '!=', request()->route('id'));
        }

        $factory = $factory->first();
        $this->factory_type = SubcontractFactoryProfile::OPERATION_TYPE[$operation_type];

        return $factory ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Duplicate $this->factory_type found!";
    }
}
