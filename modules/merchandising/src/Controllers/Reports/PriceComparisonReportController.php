<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Exports\PriceComparisonReportExport;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Services\PriceComparisonReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PriceComparisonReportController extends Controller
{
    public function index()
    {
        $items = ItemGroup::query()
            ->with('item', 'consUOM')
            ->whereHas('item', Filter::applyFilter('item_name', 'Accessories'))
            ->get();
        return view('merchandising::reports.price_comparison_report.index', compact('items'));
    }

    public function getReport(Request $request)
    {
        $reportData = (new PriceComparisonReportService($request))->report();
        return view('merchandising::reports.price_comparison_report.table', $reportData);
    }

    public function getReportPdf(Request $request)
    {
        $reportData = (new PriceComparisonReportService($request))->report();
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::reports.price_comparison_report.pdf', $reportData)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('price_comparison_report.pdf');
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new PriceComparisonReportService($request))->report();
        return Excel::download(new PriceComparisonReportExport($reportData), 'price_comparison_report.xlsx');
    }
}
