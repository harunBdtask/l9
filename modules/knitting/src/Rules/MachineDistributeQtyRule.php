<?php

namespace SkylarkSoft\GoRMG\Knitting\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramMachineDistribution;

class MachineDistributeQtyRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return (double)$this->programInfo()->program_qty >= $value && (double)$this->programInfo()->program_qty >= $this->totalMachineDistribute();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "Already Distributed {$this->totalMachineDistribute()} Which Is Equal To Program Qty.";
    }


    private function programInfo()
    {
        return KnittingProgram::query()->findOrFail(request()->input('knitting_program_id'));
    }

    private function totalMachineDistribute()
    {
        return KnittingProgramMachineDistribution::query()->where([
            'knitting_program_id' => request()->input('knitting_program_id'),
        ])->sum('distribution_qty');
    }
}
