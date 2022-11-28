<?php

namespace SkylarkSoft\GoRMG\Knitting\Actions\KnittingQC;

use SkylarkSoft\GoRMG\Knitting\Models\KnitCard;
use SkylarkSoft\GoRMG\Knitting\Models\KnitProgramRoll;

class KnitCardRollQCAction
{
    public static function handle($knitCardId) {
        $isAllRollQC = KnitProgramRoll::query()
            ->where('knit_card_id', $knitCardId)
            ->whereNull('qc_status')
            ->exists();

        $totalQCPassRoll = KnitProgramRoll::query()
            ->where('knit_card_id', $knitCardId)
            ->sum('qc_roll_weight');

        KnitCard::query()->firstOrNew(['id' => $knitCardId])->update([
            'qc_pass_qty' => $totalQCPassRoll,
            'qc_pending_status' => $isAllRollQC ? 0 : 1
        ]);
    }
}
