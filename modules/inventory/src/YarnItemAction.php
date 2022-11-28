<?php

namespace SkylarkSoft\GoRMG\Inventory;

class YarnItemAction
{
    public static function itemCriteria($yarn): array
    {
        return [
            'uom_id' => $yarn['uom_id'],
            'yarn_lot' => $yarn['yarn_lot'],
            'store_id' => $yarn['store_id'],
            'yarn_color' => $yarn['yarn_color'],
            'yarn_brand' => $yarn['yarn_brand'],
            'yarn_type_id' => $yarn['yarn_type_id'],
            'yarn_count_id' => $yarn['yarn_count_id'],
            'yarn_composition_id' => $yarn['yarn_composition_id'],
        ];
    }
}
