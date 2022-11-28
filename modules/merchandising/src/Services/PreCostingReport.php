<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use SkylarkSoft\GoRMG\Merchandising\Models\PreCosting\PreCosting;

class PreCostingReport
{
    public static function reportData($factoryId, $buyerId, $seasonId, $itemId, $styleName, $fromDate, $toDate)
    {
        return PreCosting::query()->where('factory_id', $factoryId)
            ->when($buyerId, function ($query) use ($buyerId) {
                return $query->where('buyer_id', $buyerId);
            })->when($seasonId, function ($query) use ($seasonId) {
                return $query->where('season_id', $seasonId);
            })->when($itemId, function ($query) use ($itemId) {
                return $query->where('item_id', $itemId);
            })->when($styleName, function ($query) use ($styleName) {
                return $query->where('style', $styleName);
            })->when(($fromDate && $toDate), function ($query) use ($fromDate, $toDate) {
                return $query->whereBetween('create_date', [date_format(date_create($fromDate), 'Y-m-d'), date_format(date_create($toDate), 'Y-m-d')]);
            })->with('factory:id,factory_name', 'buyer:id,name', 'season:id,season_name', 'item:id,name')
            ->get();

    }

}