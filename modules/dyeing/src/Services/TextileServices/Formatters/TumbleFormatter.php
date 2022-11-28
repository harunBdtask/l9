<?php


namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Tumble\Tumble;

class TumbleFormatter
{
     /**
     * @param Stentering $stentering
     * @return array
     */

     public function format(Tumble $tumble)
     {
         $tumble->load(['tumbleDetails','textileOrder','dyeingBatch','shift']);

         return array_merge($tumble->toArray(), [
            'tumble_details' => $tumble->getRelation('tumbleDetails')
                ->map(function ($collection) {
                    return array_merge($collection->toArray(), [
                        'dia_type_value' => $collection->dia_type_value['name'],
                        'fabric_description' => $collection->fabric_composition_value,
                        'color_name' => $collection->color->name,
                        'req_order_qty' => $collection->batch_qty ?? $collection->order_qty,
                    ]);
                }),
        ]);
     }

}