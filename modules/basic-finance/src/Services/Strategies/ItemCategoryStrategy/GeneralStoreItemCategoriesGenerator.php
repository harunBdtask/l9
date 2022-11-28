<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Strategies\ItemCategoryStrategy;

use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvItemCategory;

class GeneralStoreItemCategoriesGenerator implements ItemCategoryContract
{

    public function handle($strategy)
    {
        return GsInvItemCategory::query()->get(['id', 'name as text']);
    }
}
