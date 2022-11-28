<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Strategies\ItemStrategy;

use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;

class MerchandisingItemsGenerator implements ItemContracts
{

    public function handle($strategy)
    {
        return ItemGroup::query()->with('consUOM')
            ->where('item_id', $strategy->getCategoryId())
            ->get()->map(function ($collection) {
                return [
                    'id' => $collection->id,
                    'text' => $collection->item_group,
                    'brand_id' => null,
                    'brand_name' => null,
                    'uom_id' => $collection->cons_uom,
                    'uom_name' => $collection->consUOM->unit_of_measurement,
                    'item_description' => null,
                ];
            });
    }
}
