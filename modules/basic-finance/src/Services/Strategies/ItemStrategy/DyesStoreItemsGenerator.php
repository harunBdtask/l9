<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Strategies\ItemStrategy;

use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;

class DyesStoreItemsGenerator implements ItemContracts
{

    public function handle($strategy)
    {
        return DsItem::query()->with(['brand', 'uomDetails'])
            ->where('category_id', $strategy->getCategoryId())
            ->get()->map(function ($collection) {
                return [
                    'id' => $collection->id,
                    'text' => $collection->name,
                    'brand_id' => $collection->brand_id,
                    'brand_name' => $collection->brand->name,
                    'uom_id' => $collection->uom,
                    'uom_name' => $collection->uomDetails->name,
                    'item_description' => $collection->description,
                ];
            });
    }
}
