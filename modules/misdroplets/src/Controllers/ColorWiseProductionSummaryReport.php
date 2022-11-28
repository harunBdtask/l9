<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Controllers;

use PDF, Excel;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Misdroplets\Exports\ColorWiseProductionReportExport;

class ColorWiseProductionSummaryReport extends Controller
{
    /**
     * Generate Color Wise Production Summary Report View
     * 
     * @param Request
     * @return View
     */
    public function colorWiseProductionSummaryReport(Request $request): View
    {
        $from_date = $request->from_date ?? date('Y-m-d');
        $to_date = $request->to_date ?? date('Y-m-d');
        $page = $request->page ?? 1;
        
        $frmDate = Carbon::parse($from_date);
        $toDate = Carbon::parse($to_date);
        $diff = $frmDate->diffInDays($toDate);

        if ($diff > 30) {
            session()->flash('error', 'Please enter maximum one month date range');
            return redirect('color-wise-production-summary-report');
        }

        $reportData = $this->getColorWiseProductionSummaryReportForDownload($from_date, $to_date, $page);

        return view('misdroplets::reports.color_wise_production_summary_report', [
            'reportData' => $reportData,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'print' => 0,
        ]);
    }

    /**
     * Download Color Wise Production Summary Report
     * 
     * @param $type
     * @param $from_date
     * @param $to_date
     * @param $current_page
     */
    public function colorWiseProductionSummaryReportDownload($type, $from_date, $to_date, $current_page)
    {
        if ($type == 'pdf') {
            $reportData = $this->getColorWiseProductionSummaryReportForDownload($from_date, $to_date, $current_page);
            $print = 1;
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView(
                    'misdroplets::reports.downloads.pdf.color_wise_production_summary_report_download',
                    compact('reportData', 'from_date', 'to_date', 'print')
                )->setPaper('a4')->setOrientation('landscape');

            return $pdf->stream('color-wise-production-report.pdf');
        } else {
            return Excel::download(new ColorWiseProductionReportExport($from_date, $to_date), 'color-wise-production-report.xlsx');
        }
    }

     /**
     * Generate Color Wise Production Summary Report
     * 
     * @param $from_date
     * @param $to_date
     * @param $current_page
     * @return LengthAwarePaginator
     */
    private function getColorWiseProductionSummaryReportForDownload($from_date, $to_date, $current_page): LengthAwarePaginator
    {
        Paginator::currentPageResolver(function () use ($current_page) {
            return $current_page;
        });

        return DateAndColorWiseProduction::orderBy('production_date', 'asc')
            ->orderBy('purchase_order_id', 'desc')
            ->where('production_date', '>=', $from_date)
            ->where('production_date', '<=', $to_date)
            ->paginate();
    }
}
