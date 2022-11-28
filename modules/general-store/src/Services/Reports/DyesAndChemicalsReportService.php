<?php


namespace SkylarkSoft\GoRMG\GeneralStore\Services\Reports;


use SkylarkSoft\GoRMG\Settings\Models\Item;

class DyesAndChemicalsReportService
{
    public static function data()
    {
        return Item::query()->whereHas('category.parent', function ($query) {
            return $query->where('name', 'LIKE', 'Dyes & Chemicals');
        })->orderBy("name", "ASC")->get();
    }
}
