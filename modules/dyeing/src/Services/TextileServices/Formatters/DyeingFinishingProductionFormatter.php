<?php


namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingFinishingProduction\DyeingFinishingProduction;


class DyeingFinishingProductionFormatter
{

    /**
     * @param DyeingFinishingProduction $dyeingFinishingProduction
     * @return array
     */
    public function format(DyeingFinishingProduction $dyeingFinishingProduction): array
    {
        $dyeingFinishingProduction->load('subDyeingUnit', 'machine', 'shift',
            'buyer', 'dyeingBatch', 'textileOrder', 'finishingProductionDetails');

        return array_merge($dyeingFinishingProduction->toArray(), [
            'machine' => $dyeingFinishingProduction->getRelation('machine')->name,
            'finishing_production_details' => $dyeingFinishingProduction->getRelation('finishingProductionDetails')
                ->map(function ($collection) {
                    return array_merge($collection->toArray(), [
                        'dia_type_value' => $collection->dia_type_value['name'],
                        'fabric_description' => $collection->fabric_composition_value,
                        'color_name' => $collection->color->name,
                    ]);
                }),
        ]);
    }

}
