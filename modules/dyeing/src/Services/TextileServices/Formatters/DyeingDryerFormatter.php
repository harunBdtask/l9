<?php


namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Dryer\Dryer;

class DyeingDryerFormatter
{
    /**
     * @param Dryer $dryer
     * @return array
     */

    public function format(Dryer $dryer): array
    {
        $dryer->load('dyeingUnit', 'machine', 'shift',
            'buyer', 'dyeingBatch', 'textileOrder', 'dryerDetails');

        return array_merge($dryer->toArray(), [
            'machine' => $dryer->getRelation('machine')->name,
            'dryer_details' => $dryer->getRelation('dryerDetails')
                ->map(function ($collection) {
                    return array_merge($collection->toArray(), [
                        'dia_type_value' => $collection->dia_type_value,
                        'fabric_description' => $collection->fabric_composition_value,
                        'color_name' => $collection->color->name,
                    ]);
                }),
        ]);
    }
}