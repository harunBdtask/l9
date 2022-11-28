<?php

namespace SkylarkSoft\GoRMG\Knitting\Actions\KnittingProduction;

use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgramCollarCuffProduction;

class CollarCuffDetailsAction
{
    public function attach($collarCuffDetails, $knitProgramRoll)
    {
        $productionQty = 0;
        $knitRollId = $knitProgramRoll->id;
        $knitProgramId = $knitProgramRoll->knitting_program_id;

        foreach ($collarCuffDetails as $collarCuffDetail) {
            $collarCuffDetail['knitting_program_roll_id'] = $knitRollId;
            KnittingProgramCollarCuffProduction::query()->updateOrCreate([
                'id' => $collarCuffDetail['id'] ?? null,
            ], $collarCuffDetail);

            $productionQty += $collarCuffDetail['production_qty'];
        }
        $this->updateProductionQtyInProgram($knitProgramId);
    }

    public function deleteKnittingProgramCollarCuffProduction($knitProgramRoll)
    {
        $knitProgramRollId = $knitProgramRoll->id;
        $knitProgramId = $knitProgramRoll->knitting_program_id;

        KnittingProgramCollarCuffProduction::query()
            ->where('knitting_program_roll_id', $knitProgramRollId)
            ->delete();

        $this->updateProductionQtyInProgram($knitProgramId);
    }

    public function updateProductionQtyInProgram($knitProgramId)
    {
        $knitProgramData = KnittingProgram::query()
            ->where('id', $knitProgramId)
            ->first();

        $knitProgramCuffCollarProdQty = KnittingProgramCollarCuffProduction::query()
            ->where('knitting_program_id', $knitProgramId)
            ->sum('production_qty');

        $knitProgramData->update(['production_qty' => $knitProgramCuffCollarProdQty]);
    }
}
