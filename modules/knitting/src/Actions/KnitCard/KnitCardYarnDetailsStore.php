<?php

namespace SkylarkSoft\GoRMG\Knitting\Actions\KnitCard;

use Throwable;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Knitting\Models\KnitCardYarnDetail;

class KnitCardYarnDetailsStore
{
    /**
     * @throws Throwable
     */
    public static function handle($knitCard, $yarnDetails)
    {
        try {
            foreach ($yarnDetails as $key => $value) {
                $value['knit_card_id'] = $knitCard->id;
                $value['plan_info_id'] = $knitCard->plan_info_id;
                $knitCardYarn = KnitCardYarnDetail::query()->firstOrNew(['id' => $value['id'] ?? null]);
                $knitCardYarn->fill($value)->save();
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
