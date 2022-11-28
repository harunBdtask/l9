<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Strategies\ItemCategoryStrategy;

use SkylarkSoft\GoRMG\DyesStore\Models\DsInvItemCategory;

class DyesStoreItemCategoriesGenerator implements ItemCategoryContract
{

    public function handle($strategy)
    {
        return DsInvItemCategory::query()->get(['id', 'name as text']);
    }
}
