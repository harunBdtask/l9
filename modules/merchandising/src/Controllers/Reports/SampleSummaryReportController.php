<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Reports;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisitionDetail;
use SkylarkSoft\GoRMG\Merchandising\Exports\SampleSummaryReportExport;
use PDF;
use Excel;
use SkylarkSoft\GoRMG\Merchandising\Services\Report\SampleSummaryReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class SampleSummaryReportController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth()->format('m/d/Y');
        $buyer = Buyer::query()->get();
        $dealingMerchant = User::query()->whereIn('id',
            SampleRequisition::query()->pluck('dealing_merchant_id')->unique()->values()
        )->get();
        $style = SampleRequisition::query()->pluck('style_name')->unique()->values();
        $sample = GarmentsSample::query()->where('status', 'active')->get();

        return view("merchandising::reports.sample_summary_report.index", [
            'buyer' => $buyer,
            'style' => $style,
            'sample' => $sample,
            'startOfMonth' => $startOfMonth,
            'dealingMerchant' => $dealingMerchant,
        ]);
    }

    public function view(Request $request, SampleSummaryReportService $reportService)
    {
        $samples = $reportService->getReportData($request);
        $sampleStage = SampleRequisition::SAMPLE_STAGES;

        return view("merchandising::reports.sample_summary_report.table", [
            'samples' => $samples,
            'sampleStage' => $sampleStage
        ]);
    }

    public function pdf(Request $request, SampleSummaryReportService $reportService)
    {
        $samples = $reportService->getReportData($request);
        $sampleStage = SampleRequisition::SAMPLE_STAGES;

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView("merchandising::reports.sample_summary_report.pdf", [
                'samples' => $samples,
                'sampleStage' => $sampleStage
            ])
            ->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('sample_summary_report.pdf');
    }

    public function excel(Request $request, SampleSummaryReportService $reportService)
    {
        $samples = $reportService->getReportData($request);
        $sampleStage = SampleRequisition::SAMPLE_STAGES;

        return Excel::download(new SampleSummaryReportExport($samples, $sampleStage), 'sample_summary_report.xlsx');
    }
}
