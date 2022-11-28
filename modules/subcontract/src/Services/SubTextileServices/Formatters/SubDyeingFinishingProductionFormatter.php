<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\Formatters;

class SubDyeingFinishingProductionFormatter
{
    public function format($dyeingFinishingProduction)
    {
        $dyeingFinishingProduction->load('finishingProductionDetails');

        return array_merge($dyeingFinishingProduction->toArray(), [
            'entry_basis' => (int) $dyeingFinishingProduction->entry_basis,
            'finishing_production_details' => $dyeingFinishingProduction
                ->getRelation('finishingProductionDetails')
                ->map(function ($collection) {
                    return array_merge($collection->toArray(), [
                        'sub_dyeing_batch_no' => $collection->subDyeingBatch->batch_no,
                        'sub_textile_order_no' => $collection->subTextileOrder->order_no,
                        'dia_type_value' => $collection->dia_type_value['name'],
                        'color_name' => $collection->color->name,
                        'color_type_id' => $collection->color_type_id,
                        'color_type_value' => $collection->colorType['color_types'],
                    ]);
                }),
        ]);
    }
}
