<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Services\Reports;

use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;

class ItemsReportService
{
    public static function data($request)
    {
        $items['items'] = GsItem::with(["category", "brand", "store_details"])
            ->filter($request->query('search'))
            ->orderBy("id", "DESC")->paginate();

        return $items;
    }
}
