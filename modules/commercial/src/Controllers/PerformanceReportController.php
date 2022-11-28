<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Commercial\Exports\PerformanceReportExcel;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLC;
use SkylarkSoft\GoRMG\Commercial\Services\Report\PerformanceReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class PerformanceReportController extends Controller
{
    public function index()
    {
        $companies = Factory::query()->get(['id', 'factory_name']);
        return view('commercial::reports.performance-report.performance_report', compact('companies'));
    }

    public function fetchBankFileNos(): JsonResponse
    {
        try {
            $companyId = request('company_id');
            $data = ExportLC::query()
                ->where('beneficiary_id', $companyId)
                ->whereNotNull('bank_file_no')
                ->pluck('bank_file_no');
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchBankFileData(): JsonResponse
    {
        try {
            $bankFileNo = request('bank_file_no');
            $buyers = [];
            $styles = [];

            $exportLC = ExportLC::query()
                ->with(['details.po.buyer', 'details.po.order'])
                ->whereNotNull('bank_file_no')
                ->where('bank_file_no', $bankFileNo)
                ->select('id')
                ->first();

            if ($exportLC) {
                foreach ($exportLC->details as $detail) {
                    if ($detail->po && optional($detail->po)->buyer) {
                        $buyers[] = (object)[
                            'id' => $detail->po->buyer_id ?? '',
                            'text' => $detail->po->buyer->name ?? ''
                        ];
                    }
                    if ($detail->po && optional($detail->po)->order) {
                        $styles[] = (object)[
                            'id' => $detail->po->order_id ?? '',
                            'text' => $detail->po->order->style_name ?? '',
                            'unique_id' => $detail->po->order->job_no ?? ''
                        ];
                    }
                }
                $buyers = collect($buyers)->unique();
                $styles = collect($styles)->unique();
            }

            return response()->json([
                'buyers' => $buyers,
                'styles' => $styles
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getReport(Request $request)
    {
        $reportData = (new PerformanceReportService($request))->report();
        return view('commercial::reports.performance-report.performance_report_table', $reportData);
    }

    public function getReportPdf(Request $request)
    {
        $reportData = (new PerformanceReportService($request))->report();
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('commercial::reports.performance-report.performance_report_pdf', $reportData)
            ->setPaper('a4')->setOrientation('landscape')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        return $pdf->stream('performance_report.pdf');
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new PerformanceReportService($request))->report();
        return Excel::download(new PerformanceReportExcel($reportData), 'performance_report.xlsx');
    }
}
