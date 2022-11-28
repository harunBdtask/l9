<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Strategies\ItemCategoryStrategy;

use SkylarkSoft\GoRMG\SystemSettings\Models\Item;

class MerchandisingItemCategoriesGenerator implements ItemCategoryContract
{

    public function handle($strategy)
    {
        return Item::query()->get(['id', 'item_name as text']);
    }
}
