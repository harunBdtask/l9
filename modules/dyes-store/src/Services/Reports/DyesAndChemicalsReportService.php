<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services\Reports;

use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;

class DyesAndChemicalsReportService
{
    public static function data()
    {
        return DsItem::query()->whereHas('category.parent', function ($query) {
            return $query->where('name', 'LIKE', 'Dyes & Chemicals');
        })->orderBy("name", "ASC")->get();
    }
}
