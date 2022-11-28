<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingGoodsDelivery\DyeingGoodsDelivery;

class DyeingGoodsDeliveryAction
{

    public function storeDetails(DyeingGoodsDelivery $dyeingGoodsDelivery, $dyeingGoodsDeliveryDetails)
    {
        $dyeingGoodsDelivery->dyeingGoodsDeliveryDetails()->createMany($dyeingGoodsDeliveryDetails);
    }

    public function updateDetails(DyeingGoodsDelivery $dyeingGoodsDelivery, $dyeingGoodsDeliveryDetails)
    {
        foreach ($dyeingGoodsDeliveryDetails as $detail) {
            $dyeingGoodsDelivery->dyeingGoodsDeliveryDetails()->updateOrCreate([
                'id' => $detail['id']
            ], $detail);
        }
    }

}