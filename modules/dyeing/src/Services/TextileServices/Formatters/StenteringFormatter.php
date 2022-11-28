<?php


namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\Stentering\Stentering; 

class StenteringFormatter
{
     /**
     * @param Stentering $stentering
     * @return array
     */

     public function format(Stentering $stentering)
     {
         $stentering->load(['stenteringDetails','textileOrder','dyeingBatch','dyeingUnit','shift','machine']);

         return array_merge($stentering->toArray(), [
            'machine' => $stentering->getRelation('machine')->name,
            'stentering_details' => $stentering->getRelation('stenteringDetails')
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