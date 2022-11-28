<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Services\Reports;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvTransaction;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;

class DailyReportService
{
    public static function data($store, $date, $type)
    {
        $items = GsItem::with("uomDetails")->orderBy("name", "ASC");
        $responseData['items'] = $type !== "excel" ? $items->paginate() : $items->get();
        $responseData['store'] = $store;
        $responseData['store_name'] = get_store_name($store);
        $responseData['reporting_date'] = $date;
        $responseData['first_date'] = $date;
        $responseData['last_date'] = $date;
        $responseData['type'] = $type;
        return $responseData;
    }

    private static function transactionsInDateRange($store, $firstDate)
    {
        return GsInvTransaction::where('store', $store)
            ->with(["item.uomDetails:id,name"])
            ->where('trn_date', "=", $firstDate)
            ->select(DB::raw('*, qty * rate as total'))
            ->get()
            ->toArray();
    }
}
