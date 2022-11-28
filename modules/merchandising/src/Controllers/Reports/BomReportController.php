<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Reports;

use App\Http\Controllers\Controller;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Services\Report\ReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class BomReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->pluck("name as text", "id");
        $signature = ReportSignatureService::getSignatures("BOM REPORT");
        return view("merchandising::reports.bom_report.index", compact('buyers', 'signature'));
    }

    public function fetchReport(Request $request)
    {
        $data = ReportService::getBomReport($request);
        $signature = ReportSignatureService::getSignatures("BOM REPORT", $request->get('buyer_id'));
        if ($request->get('type') === "print") {
            return view("merchandising::reports.bom_report.print", $data, ['signature' => $signature]);
        } elseif ($request->get('type') === "pdf") {
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('merchandising::reports.bom_report.pdf', $data, ['signature' => $signature])
                ->setPaper('a4')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer', compact('signature')),
                ]);

            return $pdf->stream($data['job_no'] . '_BOM.pdf');
        } else {
            return view("merchandising::reports.bom_report.table", $data, ['signature' => $signature]);
        }
    }
    //Bom Report Checklist
    public function checklist()
    {
       $buyers = Buyer::query()->pluck("name as text", "id");
       $signature = ReportSignatureService::getSignatures("BOM REPORT");
       return view("merchandising::reports.bom_report.checklist", compact('buyers', 'signature'));
    }
    //Bom Report Checklist Fetch
    public function fetchReportCheckList(Request $request)
    {
       $data = ReportService::getBomReport($request);
       $signature = ReportSignatureService::getSignatures("BOM REPORT", $request->get('buyer_id'));

       if ($request->get('type') === "print") {
           return view("merchandising::reports.bom_report.checklist_print", $data, ['signature' => $signature]);
       } elseif ($request->get('type') === "pdf") {
           $pdf = PDF::setOption('enable-local-file-access', true)
               ->loadView('merchandising::reports.bom_report.checklist_pdf', $data, ['signature' => $signature])
               ->setPaper('a4')->setOptions([
                   'header-html' => view('skeleton::pdf.header'),
                   'footer-html' => view('skeleton::pdf.footer', compact('signature')),
               ]);
           return $pdf->stream($data['job_no'] . '_BOM.pdf');
       } else {
           return view("merchandising::reports.bom_report.checklist_table", $data, ['signature' => $signature]);
       }
    }
}
