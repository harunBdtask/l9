<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\States\StoreStates;

use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class FabricStoreBasis implements StoreStatesContract
{

    public function handle()
    {
        $itemCategoryId = Item::query()->where('item_name', 'Knit Finish Fabrics')
                              ->first()['id'] ?? null;

        return Store::query()->when($itemCategoryId, Filter::applyFilter('item_category_id', $itemCategoryId))
            ->get(['id', 'name as text']);
    }

}
