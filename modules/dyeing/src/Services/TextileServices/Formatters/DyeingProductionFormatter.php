<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingProduction\DyeingProduction;

class DyeingProductionFormatter
{

    public function format(DyeingProduction $dyeingProduction): array
    {
        $dyeingProduction->load('dyeingUnit', 'dyeingBatch.machineAllocations', 'shift', 'dyeingProductionDetails');

        return array_merge($dyeingProduction->toArray(), [
            'dyeing_unit' => $dyeingProduction->getRelation('dyeingUnit')->name,
            'machine_name' => collect($dyeingProduction->dyeingBatch->machineAllocations)
                    ->pluck('machine.name')->implode(', ') ?? null,
            'dyeing_production_details' => $dyeingProduction->dyeingProductionDetails
                ->map(function ($collection) {
                    return array_merge($collection->toArray(), [
                        'fabric_color' => $collection->color->name,
                        'dia_type' => $collection->dia_type_value['name'],
                        'fabric_description' => $collection->fabric_composition_value,
                    ]);
                }),
        ]);
    }
}
