<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\Formatters;

class SubDyeingTumbleFormatter
{
    public function format($dyeingTumble)
    {
        $dyeingTumble->load('tumbleDetails');

        return array_merge($dyeingTumble->toArray(), [
            'entry_basis' => (int) $dyeingTumble->entry_basis,
            'tumble_details' => $dyeingTumble
                ->getRelation('tumbleDetails')
                ->map(function ($collection) {
                    return array_merge($collection->toArray(), [
                        'sub_dyeing_batch_no' => $collection->subDyeingBatch->batch_no,
                        'sub_textile_order_no' => $collection->subTextileOrder->order_no,
                        'dia_type_value' => $collection->dia_type_value['name'],
                        'color_name' => $collection->color->name,
                        'color_type_value' => $collection->colorType['color_types'],
                    ]);
                }),
        ]);
    }
}
