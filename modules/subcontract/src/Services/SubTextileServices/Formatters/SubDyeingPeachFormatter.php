<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\Formatters;

class SubDyeingPeachFormatter
{
    /**
     * @param $dyeingPeach
     * @return array
     */
    public function format($dyeingPeach): array
    {
        $dyeingPeach->load('peachDetails');

        return array_merge($dyeingPeach->toArray(), [
            'entry_basis' => (int) $dyeingPeach->entry_basis,
            'peach_details' => $dyeingPeach
                ->getRelation('peachDetails')
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
