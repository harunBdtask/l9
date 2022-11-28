<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services\Reports;

use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;

class ItemsReportService
{
    public static function data($request): array
    {
        $items['items'] = DsItem::with(["category", "brand", "store_details"])
            ->filter($request->query('search'))
            ->orderBy("id", "DESC")->paginate();

        return $items;
    }
}
