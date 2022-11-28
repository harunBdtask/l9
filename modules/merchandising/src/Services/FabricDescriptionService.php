<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;

class FabricDescriptionService
{
    /**
     * @param $fabric_composition_id
     * @return string|null
     */
    public static function description($fabric_composition_id): ?string
    {
        $fabric_composition = NewFabricComposition::with(['newFabricCompositionDetails.yarnComposition'])
            ->where("id", $fabric_composition_id)
            ->first();

        if (!isset($fabric_composition)) {
            return null;
        }

        $composition = '';
        $first_key = $fabric_composition->newFabricCompositionDetails->keys()->first();
        $last_key = $fabric_composition->newFabricCompositionDetails->keys()->last();
        $fabric_composition->newFabricCompositionDetails->each(function ($fabric_item, $key) use (&$composition, $first_key, $last_key, $fabric_composition) {
            $composition .= ($key === $first_key) ? "{$fabric_composition->construction} [" : '';
            $composition .= "{$fabric_item->yarnComposition->yarn_composition} {$fabric_item->percentage}%";
            $composition .= ($key !== $last_key) ? ', ' : ']';
        });

        return $composition;
    }
}
