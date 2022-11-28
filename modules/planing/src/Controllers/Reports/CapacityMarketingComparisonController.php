<?php


namespace SkylarkSoft\GoRMG\Planing\Controllers\Reports;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\Planing\Exports\CapacityMarketingComparisonReportExport;
use SkylarkSoft\GoRMG\Planing\ValueObjects\CapacityMarketingComparisonValueObject;
use PDF;

class CapacityMarketingComparisonController extends Controller
{
    public function index(Request $request, CapacityMarketingComparisonValueObject $comparisonValueObject)
    {
        $reports = [];
        if ($request->get('start') && $request->get('end')) {
            $comparisonValueObject->setStartDate($request->get('start'));
            $comparisonValueObject->setEndDate($request->get('end'));
            $reports = $comparisonValueObject->report();
        }
        return view('planing::reports.capacity-marketing-comparison', [
            'reports' => $reports,
        ]);
    }

    public function pdf(Request $request, CapacityMarketingComparisonValueObject $comparisonValueObject)
    {
        $reports = [];
        if ($request->get('start') && $request->get('end')) {
            $comparisonValueObject->setStartDate($request->get('start'));
            $comparisonValueObject->setEndDate($request->get('end'));
            $reports = $comparisonValueObject->report();
        }
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('planing::reports.downloadable.pdf.capacity-marketing-comparison-pdf',
                ['reports' => $reports])
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer',),
            ]);
        return $pdf->stream('capacity_marketing_comparison.pdf');
    }

    public function excel(Request $request, CapacityMarketingComparisonValueObject $comparisonValueObject)
    {
        $reports = [];
        if ($request->get('start') && $request->get('end')) {
            $comparisonValueObject->setStartDate($request->get('start'));
            $comparisonValueObject->setEndDate($request->get('end'));
            $reports = $comparisonValueObject->report();
        }
        return Excel::download(new CapacityMarketingComparisonReportExport(['reports' => $reports]), 'capacity_marketing_comparison.xlsx');
    }
}
