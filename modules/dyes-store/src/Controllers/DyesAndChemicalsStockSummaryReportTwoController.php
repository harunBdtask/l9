<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use SkylarkSoft\GoRMG\DyesStore\Services\Reports\DyesAndChemicalsReportService;

class DyesAndChemicalsStockSummaryReportTwoController extends Controller
{
    public function reportData()
    {
        $firstDate = request('first_date') ?? Carbon::now()->startOfMonth()->toDateString();
        $lastDate = request('last_date') ?? Carbon::now()->endOfMonth()->toDateString();
        $storeId = request('store_id');

        $items = DyesAndChemicalsReportService::data();

        return view('dyes-store::report.dyes_chemicals.stock_summary_report_two', [
            'first_date' => $firstDate,
            'last_date' => $lastDate,
            'storeId' => $storeId,
            'items' => $items,
            'type' => 'dyesChemicalsReceive'
        ]);
    }
}
