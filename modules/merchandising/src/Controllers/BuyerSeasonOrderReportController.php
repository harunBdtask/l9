<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Exports\BuyerSeasonOrderListExport;
use SkylarkSoft\GoRMG\Merchandising\Services\BuyerSeasonOrderReportService;
use SkylarkSoft\GoRMG\Merchandising\Services\Report\ReportViewService;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BuyerSeasonOrderReportController extends Controller
{
    public function index()
    {
        $buyers = Buyer::query()->get(['name', 'id']);
        return view("merchandising::reports.buyer_season_order_report", compact('buyers'));
    }

    public function getReport(Request $request): JsonResponse
    {
        $reportData = (new BuyerSeasonOrderReportService($request))->report();
        $reportData['buyer'] = Buyer::query()->where('id', $request->get('buyer_id'))->first()->name ?? null;
        $reportDataWithChart = [
            'status' => false,
        ];

        if ($request->get('buyer_id') != 'all') {
            $reportDataWithChart = $this->generateChart($request, $reportData, $reportDataWithChart);
        }

        $reportDataWithChart['view'] = (string)view('merchandising::reports.includes.buyer_season_order_report_table', $reportData);

        return response()->json($reportDataWithChart);
    }

    public function getReportPdf(Request $request): Response
    {
        $reportData = (new BuyerSeasonOrderReportService($request))->report();

        if (request('type') === "images") {
            $signature = ReportSignatureService::getSignatures("BUYER SEASON ORDER IMAGE REPORT", $reportData['buyer_id']);
        } else {
            $signature = ReportSignatureService::getSignatures("BUYER SEASON ORDER REPORT", $reportData['buyer_id']);
        }
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::reports.downloads.pdf.buyer_season_order_report_pdf', $reportData, ['signature' => $signature])
            ->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer', compact('signature')),
            ]);

        return $pdf->download($reportData['buyer'] . '_list.pdf');
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $reportData = (new BuyerSeasonOrderReportService($request))->report();

        return Excel::download(new BuyerSeasonOrderListExport($reportData), $reportData['buyer'] . '_list.xlsx');
    }

    public function getReportPrint(Request $request)
    {
        $reportData = (new BuyerSeasonOrderReportService($request))->report();
        if (request('type') === "images") {
            $signature = ReportSignatureService::getSignatures("BUYER SEASON ORDER IMAGE REPORT", $reportData['buyer_id']);
        } else {
            $signature = ReportSignatureService::getSignatures("BUYER SEASON ORDER REPORT", $reportData['buyer_id']);
        }
        return view('merchandising::reports.buyer_season_order_report_print', $reportData, ['signature' => $signature]);
    }

    public function getBuyersSeasons($id): JsonResponse
    {
        $seasons = Season::query()->where('buyer_id', $id)->get(['season_name', 'id']);

        return response()->json($seasons);
    }

    protected function generateChart(Request $request, $reportData, $reportDataWithChart)
    {
        if ($request->get('season')) {
            $reportDataWithChart['status'] = false;
        } else {
            $reportCollection = collect($reportData['reports'])->groupBy('season', true)->map(function ($group) {
                return [
                    'season' => $group->first()['season'],
                    'total_po_fob_value' => $group->sum('po_fob_value')
                ];
            });
            $reportChartData = [];
            foreach ($reportCollection as $item) {
                $reportChartData['keys'][] = $item['season'];
                $reportChartData['values'][] = $item['total_po_fob_value'];
                $reportChartData['colors'][] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
            }
            $reportDataWithChart['status'] = true;
            $reportDataWithChart['chart'] = $reportChartData;
        }
        return $reportDataWithChart;
    }
}
