<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Session;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\DailyChallanSizeWiseExport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DailyChallanSizeWiseInput;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DailySizeWiseInputReportController
{
    public function index(Request $request)
    {
        try {
            $date = $request->get('date', date('Y-m-d'));

            $report = $this->generateReport($date);

            return view('inputdroplets::reports.daily_size_wise_input_report', $report);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
            return redirect()->back();
        }
    }

    private function generateReport($date): array
    {
        $inputs = DailyChallanSizeWiseInput::query()
            ->with([
                'size',
                'buyer',
                'color',
                'order',
                'garmentsItem',
                'purchaseOrder',
                'linesWithoutGlobalScopes',
                'floorsWithoutGlobalScopes',
            ])
            ->where('production_date', $date)
            ->get();

        $todaysChallan = $inputs->pluck('challan_no')->unique();

        $previousChallans = DailyChallanSizeWiseInput::query()
            ->selectRaw('*,SUM(sewing_input) as total_input')
            ->whereIn('challan_no', $todaysChallan)
            ->where('production_date', '<=', $date)
            ->groupBy(['challan_no'])
            ->get();

        $reportData = $inputs->unique('challan_no')
            ->map(function ($collection) use ($inputs, $previousChallans) {

                $collection['sizes'] = $inputs
                    ->where('challan_no', $collection->challan_no)
                    ->pluck('sewing_input', 'size.name');

                $collection['previous_total'] = $previousChallans
                    ->where('challan_no', $collection->challan_no)
                    ->sum('total_input');

                return $collection;
            });

        $sizes = $inputs->unique('size_id')->pluck('size');
        return [
            'reportData' => $reportData,
            'sizes' => $sizes
        ];
    }

    public function excel(Request $request): BinaryFileResponse
    {
        $date = $request->get('date', date('Y-m-d'));

        $report = $this->generateReport($date);

        return Excel::download(new DailyChallanSizeWiseExport($report), 'daily_size_wise_input_report.xls');
    }

    public function pdf(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));

        $report = $this->generateReport($date);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('inputdroplets::reports.downloads.pdf.daily_size_wise_input_report_pdf', $report)->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('daily_size_wise_input_report.pdf');
    }
}
