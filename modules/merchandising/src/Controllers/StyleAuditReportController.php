<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Exports\StyleAuditReportExport;
use SkylarkSoft\GoRMG\Merchandising\Exports\StyleAuditReportValueExport;
use SkylarkSoft\GoRMG\Merchandising\Services\StyleAuditReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class StyleAuditReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->select('name', 'id')->get();

        return view('merchandising::reports.style_audit_report', compact('buyers'));
    }

    public function indexValue()
    {
        $buyers = Buyer::query()->select('name', 'id')->get();
        $type = 'value';

        return view('merchandising::reports.style_audit_report', compact('buyers', 'type'));
    }

    public function getReport(Request $request)
    {
        $styleId = $request->get('style_id', null);
        $reportData = StyleAuditReportService::generateReport($styleId);

        return view('merchandising::reports.includes.style_audit_report_table', compact('reportData'));
    }

    public function getReportPdf(Request $request)
    {
        $styleId = $request->get('style_id', null);
        $reportData = StyleAuditReportService::generateReport($styleId);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::reports.downloads.pdf.style_audit_report_pdf', compact('reportData'))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream($reportData['order']['style_name'].'_audit_report.pdf');
    }

    public function getValueReport(Request $request)
    {
        $styleId = $request->get('style_id', null);
        $reportData = StyleAuditReportService::generateReport($styleId);

        return view('merchandising::reports.includes.style_audit_report_value_table', compact('reportData'));
    }

    public function getValueReportPdf(Request $request)
    {
        $styleId = $request->get('style_id', null);
        $reportData = StyleAuditReportService::generateReport($styleId);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::reports.downloads.pdf.style_audit_value_report_pdf', compact('reportData'))
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream($reportData['order']['style_name'].'_audit_report.pdf');
    }

    public function getAuditExcel(Request $request)
    {
        $styleId = $request->get('style_id', null);
        $reportData = (new StyleAuditReportService())->generateReport($styleId);
        //dd($reportData);

        return Excel::download(new StyleAuditReportExport($reportData), 'style_audit_report.xlsx');
    }

    public function getAuditValueExcel(Request $request)
    {
        $styleId = $request->get('style_id', null);
        $reportData = (new StyleAuditReportService())->generateReport($styleId);
        //dd($reportData);

        return Excel::download(new StyleAuditReportValueExport($reportData), 'style_audit_value_report.xlsx');
    }
}
