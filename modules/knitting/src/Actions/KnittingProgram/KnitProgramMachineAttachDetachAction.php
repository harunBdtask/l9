<?php

namespace SkylarkSoft\GoRMG\Knitting\Actions\KnittingProgram;

use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramMachineDistribution;
use SkylarkSoft\GoRMG\SystemSettings\Models\Machine;

class KnitProgramMachineAttachDetachAction
{
    public function attach($knittingProgram, $machineNo)
    {
        $knitProgramData = KnittingProgram::query()->where('id', $knittingProgram)->first();
        $attachMachineToKnitProgram = collect($knitProgramData->machine_nos)
            ->merge($machineNo)
            ->unique()
            ->values();
        $knitProgramData->update(['machine_nos' => $attachMachineToKnitProgram]);
        $knitProgramData->update(['machines_capacity' => $this->knitMachineCapacity($knittingProgram)]);
    }

    public function detach($knitProgramId, $machineNo)
    {
        $knitProgramData = KnittingProgram::query()
            ->where('id', $knitProgramId)
            ->first();

        $detachMachineToKnitProgram = collect($knitProgramData->machine_nos)
            ->filter(function ($value) use ($machineNo) {
                return $value != $machineNo;
            })->toArray();
        
        $knitProgramData->update(['machine_nos' => $detachMachineToKnitProgram]);
        $knitProgramData->update([
            'machines_capacity' => $this->knitMachineCapacity($knitProgramId)
        ]);
    }

    private function knitMachineCapacity($knittingProgram)
    {
        $knitMachinesId = KnittingProgramMachineDistribution::query()
            ->where('knitting_program_id', $knittingProgram)
            ->pluck('machine_id');
        return Machine::query()->whereIn('id', $knitMachinesId)
            ->sum('machine_capacity');
    }
}
