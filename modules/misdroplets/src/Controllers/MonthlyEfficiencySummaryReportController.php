<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Misdroplets\Models\MonthlyEfficiencySummaryReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use Carbon\Carbon;

class MonthlyEfficiencySummaryReportController extends Controller
{

    public function getMonthlyEfficiencySummaryReport(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month ?? (int)date('m');

        $reportData = $this->getMonthlyEfficiencySummaryReportData($year, $month);

        return view('misdroplets::reports.monthly_efficiency_summary_report', [
            'daysOfMonth' => $reportData['daysOfMonth'],
            'month' => $month,
            'year' => $year,
            'monthName' => $reportData['monthName'],
            'lines' => $reportData['lines'],
            'factoryEfficiency' => $reportData['factoryEfficiency'],
            'print' => 0
        ]);
    }

    public function getMonthlyEfficiencySummaryReportData($year, $month): array
    {
        $carbonParsedDate = Carbon::create($year, $month);
        $carbonParsedDateClone = clone $carbonParsedDate;
        $startDate = $carbonParsedDate->startOfMonth()->startOfDay()->toDateString();
        $endDate = $carbonParsedDate->endOfMonth()->endOfDay()->toDateString();
        $daysOfMonth = $carbonParsedDateClone->daysInMonth;

        $reportDataQuery = MonthlyEfficiencySummaryReport::query()
            ->where('report_date', '>=', $startDate)
            ->where('report_date', '<=', $endDate)
            ->select('report_date', 'floor_id', 'line_id', 'used_minutes', 'produced_minutes', 'line_efficiency')
            ->get();
        $totalUsedMin = 0;
        $totalProducedMin = 0;

        $lines = Line::query()
            ->leftJoin('floors', 'floors.id', 'lines.floor_id')
            ->selectRaw('lines.id as id, lines.floor_id as floor_id, lines.line_no as line_no, lines.sort as sort, floors.floor_no')
            ->orderBy('lines.sort', 'asc')
            ->get();

        foreach ($lines as $key => $line) {
            $efficiencyData = [];
            for ($day = 1; $day <= $daysOfMonth; $day++) {

                $report_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-' . str_pad($day, 2, "0", STR_PAD_LEFT);

                $reportData = $reportDataQuery->where('line_id', $line->id)->where('report_date', $report_date)->first();

                $efficiencyData[] = $reportData;

                $totalUsedMin += $reportData->used_minutes ?? 0;
                $totalProducedMin += $reportData->produced_minutes ?? 0;
            }

            $lines[$key]->efficiencyData = $efficiencyData;
        }

        $factoryEfficiency = ($totalUsedMin > 0) ? number_format(($totalProducedMin / $totalUsedMin) * 100, 2) : '0.00';

        return [
            'factoryEfficiency' => $factoryEfficiency,
            'lines' => $lines,
            'daysOfMonth' => $daysOfMonth,
            'monthName' => date("M", mktime(0, 0, 0, $month, 10)),
        ];
    }

    public function getMonthlyEfficiencySummaryReportDownload($type, $year, $month)
    {
        $reportData = $this->getMonthlyEfficiencySummaryReportData($year, $month);
        $factoryEfficiency = $reportData['factoryEfficiency'];
        $lines = $reportData['lines'];
        $daysOfMonth = $reportData['daysOfMonth'];
        $monthName = $reportData['monthName'];

        $print = 1;
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('misdroplets::reports.downloads.pdf.monthly_efficiency_summary_report_download', compact('factoryEfficiency', 'lines', 'daysOfMonth', 'monthName', 'print', 'year'))
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('efficiency-summary-report.pdf');
        } else {
            \Excel::create('Efficiency Summary Report', function ($excel) use ($factoryEfficiency, $lines, $daysOfMonth, $monthName) {
                $excel->sheet('Efficiency Summary Report', function ($sheet) use ($factoryEfficiency, $lines, $daysOfMonth, $monthName) {
                    $sheet->loadView('misdroplets::reports.downloads.excels.monthly_efficiency_summary_report_download', compact('factoryEfficiency', 'lines', 'daysOfMonth', 'monthName'));
                });
            })->export('xls');
        }
    }
}
