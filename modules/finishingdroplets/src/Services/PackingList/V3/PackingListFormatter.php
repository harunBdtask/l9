<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Services\PackingList\V3;

use SkylarkSoft\GoRMG\Finishingdroplets\Models\GarmentPackingProduction;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\PackingList\V3\Assortments\AssortColorAssortSize;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\PackingList\V3\Assortments\AssortColorSolidSize;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\PackingList\V3\Assortments\SolidColorAssortSize;
use SkylarkSoft\GoRMG\Finishingdroplets\Services\PackingList\V3\Assortments\SolidColorSolidSize;

class PackingListFormatter
{
    public static function setAssortment($assortment)
    {
        $assortments = [
            GarmentPackingProduction::SOLID_COLOR_SOLID_SIZE => new SolidColorSolidSize(),
            GarmentPackingProduction::SOLID_COLOR_ASSORT_SIZE => new SolidColorAssortSize(),
            GarmentPackingProduction::ASSORT_COLOR_SOLID_SIZE => new AssortColorSolidSize(),
            GarmentPackingProduction::ASSORT_COLOR_ASSORT_SIZE => new AssortColorAssortSize()
        ];

        return array_key_exists($assortment, $assortments) ? $assortments[$assortment] : false;
    }
}
