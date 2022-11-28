<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\States\StoreStates;

use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class TrimsStoreBasis implements StoreStatesContract
{

    public function handle()
    {
        $itemCategoryId = Item::query()->where('item_name', 'Trims')
                              ->first()['id'];

        return Store::query()->where('item_category_id', $itemCategoryId)
            ->get(['id', 'name as text']);
    }

}
