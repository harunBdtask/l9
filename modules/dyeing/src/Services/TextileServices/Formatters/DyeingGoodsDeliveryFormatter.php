<?php


namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingGoodsDelivery\DyeingGoodsDelivery;

class DyeingGoodsDeliveryFormatter
{
     /**
     * @param DyeingGoodsDelivery $dyeingGoodsDelivery
     * @return array
     */

     public function format(DyeingGoodsDelivery $dyeingGoodsDelivery)
     {
         $dyeingGoodsDelivery->load(['dyeingGoodsDeliveryDetails','textileOrder','dyeingBatch','shift']);

         return array_merge($dyeingGoodsDelivery->toArray(), [
            'dyeing_goods_delivery_details' => $dyeingGoodsDelivery->getRelation('dyeingGoodsDeliveryDetails')
                ->map(function ($collection) {
                    return array_merge($collection->toArray(), [
                        'dia_type_value' => $collection->dia_type_value,
                        'fabric_description' => $collection->fabric_composition_value,
                        'color_name' => $collection->color->name,
                        'req_order_qty' => $collection->req_order_qty,
                    ]);
                }),
        ]);
     }

}