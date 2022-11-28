<?php

namespace SkylarkSoft\GoRMG\Knitting\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramMachineDistribution;
use SkylarkSoft\GoRMG\Knitting\Rules\MachineDistributeQtyRule;

class ProgramMachineFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'plan_info_id' => 'required',
            'knitting_program_id' => 'required',
            'machine_id' => 'required',
            'distribution_qty' => ['required', new MachineDistributeQtyRule()],
        ];
    }
}
