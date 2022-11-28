<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\GeneralStore\Exports\InventoryExcelReport;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvTransaction;
use SkylarkSoft\GoRMG\GeneralStore\Services\Calculations\AvgRateCalculator;
use SkylarkSoft\GoRMG\GeneralStore\Services\Calculations\OutRateCalculator;
use SkylarkSoft\GoRMG\GeneralStore\Services\Reports\DailyReportService;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;


class DailyReportController
{

    public function dailyReportView(Request $request, $store)
    {
        $firstDate = $request->first_date ? $request->first_date : date('Y-m-d');
        $store_name = strtolower(str_replace(" ", "_", get_store_name($store)));
        $responseData = DailyReportService::data($store, $firstDate, $request->input('type'));
        if (!$request->has('type')) {
            return view('general-store::report.daily_report', $responseData);
        }
        if ($request->input('type') == 'pdf') {
            $fileName = $store_name . '_daily_report.pdf';
            $pdf = PDF::loadView('general-store::report.daily_report_pdf', $responseData);
            return $pdf->download($fileName);
        }
        if ($request->input('type') == 'excel') {

            $stockSummeryReportExport = new InventoryExcelReport($responseData,
                'general-store::report.daily_report_excel',
                'Daily Report',
                range('A', 'M'),
                [1, 2, 3]
            );
            $fileName = $store_name . '_daily_report.xlsx';

            return Excel::download(
                $stockSummeryReportExport,
                $fileName
            );
        }
        // return view('general-store::report.daily_report', $responseData);
    }


    private function calculateOpeningQty($data): int
    {
        $transactions = collect($data);
        $totalOut = $transactions->where('trn_type', 'out')->sum('qty') ?: 0;
        $totalIn = $transactions->where('trn_type', 'in')->sum('qty') ?: 0;
        return $totalIn - $totalOut;
    }
}
