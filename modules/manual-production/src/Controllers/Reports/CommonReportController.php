<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Reports;

use App\Http\Controllers\Controller;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use SkylarkSoft\GoRMG\ManualProduction\Exports\ChallanWiseStyleInputSummaryExport;
use SkylarkSoft\GoRMG\ManualProduction\Exports\StyleWiseRejectionReportExport;
use SkylarkSoft\GoRMG\ManualProduction\Exports\YearlyRejectionReportExport;
use SkylarkSoft\GoRMG\ManualProduction\Models\ManualSewingInputProduction;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDailyProductionReport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualTotalProductionReport;
use SkylarkSoft\GoRMG\ManualProduction\Services\CommonReportService;
use SkylarkSoft\GoRMG\ManualProduction\Services\RejectionService;
use SkylarkSoft\GoRMG\ManualProduction\Services\StyleOverallReportService;
use SkylarkSoft\GoRMG\ManualProduction\Exports\StyleOverallReportExport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class CommonReportController extends Controller
{
    public function styleOverallSummaryReport(Request $request)
    {
        list($reports, $buyer_id, $order_id, $buyers, $order, $orders) = StyleOverallReportService::getReport($request);
        return view('manual-production::reports.style.style_overall_summary', [
            'reports' => $reports,
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
            'buyers' => $buyers,
            'order' => $order,
            'orders' => $orders
        ]);
    }

    public function styleOverallSummaryReportPdf(Request $request): Response
    {
        list($reports, $buyer_id, $order_id, $buyers, $order, $orders) = StyleOverallReportService::getReport($request);
        $pdf = PDF::loadView(
            'manual-production::reports.style.style_overall_summary_pdf',
            compact(
                'reports',
                'order'
            )
        )->setPaper('a4', 'landscape');
        return $pdf->stream($order . '_overall_report_' . date('d_m_Y') . '.pdf');
    }

    public function styleOverallSummaryReportExcel(Request $request): BinaryFileResponse
    {
        list($reports, $buyer_id, $order_id, $buyers, $order, $orders) = StyleOverallReportService::getReport($request);
        return Excel::download(new StyleOverallReportExport($reports, $order), $order . '_overall_report_' . date('d_m_Y') . '.xlsx');
    }

    public function challanWiseStyleInputSummary(Request $request)
    {
        $buyers = Buyer::query()->where('factory_id', factoryId())->pluck('name as text', 'id');
        $buyer_id = $request->get('buyer_id') ?? null;
        $order_id = $request->get('order_id') ?? null;
        $color_id = $request->get('color_id') ?? null;
        $orders = [];
        $reports = [];
        $colors = [];
        if ($order_id) {
            $orders = Order::query()->where('id', $order_id)->pluck('style_name', 'id');
            $reports = CommonReportService::challanWiseStyleInput($order_id, $color_id);
        }
        if ($color_id) {
            $colors = Color::query()->where('id', $color_id)->pluck('name', 'id');
        }
        return view(
            'manual-production::reports.sewing.challan_wise_style_input_summary',
            compact('color_id', 'order_id', 'buyer_id', 'orders', 'buyers', 'colors', 'reports')
        );
    }

    public function challanWiseStyleInputSummaryPdf(Request $request)
    {
        $buyers = Buyer::query()->where('factory_id', factoryId())->pluck('name as text', 'id');
        $buyer_id = $request->get('buyer_id') ?? null;
        $order_id = $request->get('order_id') ?? null;
        $color_id = $request->get('color_id') ?? null;
        $orders = [];
        $reports = [];
        if ($order_id) {
            $orders = Order::query()->where('id', $order_id)->pluck('style_name', 'id');
            $reports = CommonReportService::challanWiseStyleInput($order_id, $color_id);
        }
        $pdf = PDF::loadView(
            'manual-production::reports.sewing.challan_wise_style_input_summary_pdf',
            compact(
                'reports',
                'orders',
                'order_id',
                'buyer_id',
                'buyers'
            )
        )->setPaper('a4')->setOrientation('landscape');
        return $pdf->stream('challan_wise_input_summary.pdf');
    }

    public function challanWiseStyleInputSummaryExcel(Request $request): BinaryFileResponse
    {
        $buyers = Buyer::query()->where('factory_id', factoryId())->pluck('name as text', 'id');
        $buyer_id = $request->get('buyer_id') ?? null;
        $order_id = $request->get('order_id') ?? null;
        $color_id = $request->get('color_id') ?? null;
        $orders = [];
        $reports = [];
        if ($order_id) {
            $orders = Order::query()->where('id', $order_id)->pluck('style_name', 'id');
            $reports = CommonReportService::challanWiseStyleInput($order_id, $color_id);
        }
        return Excel::download(
            new ChallanWiseStyleInputSummaryExport($reports, $orders, $order_id, $buyer_id, $buyers),
            $orders[$order_id] . '_challan_wise_input_summary.xlsx'
        );
    }

    public function styleWiseRejectionReport(Request $request)
    {
        $buyers = Buyer::query()->where('factory_id', factoryId())->pluck('name as text', 'id');
        $buyer_id = $request->get('buyer_id') ?? null;
        $order_id = $request->get('order_id') ?? null;
        $orders = [];
        $reports = [];
        if ($order_id) {
            $orders = Order::query()->where('id', $order_id)->pluck('style_name', 'id');
            $reports = ManualTotalProductionReport::query()->where(['buyer_id' => $buyer_id, 'order_id' => $order_id])
                ->get();
        }
        return view(
            'manual-production::reports.rejection.style_wise_rejection_report',
            compact('buyers', 'buyer_id', 'order_id', 'orders', 'reports')
        );
    }

    public function styleWiseRejectionReportPdf(Request $request)
    {
        $buyer_id = $request->get('buyer_id') ?? null;
        $order_id = $request->get('order_id') ?? null;
        $orders = [];
        $reports = [];
        if ($order_id) {
            $orders = Order::query()->where('id', $order_id)->pluck('style_name', 'id');
            $reports = ManualTotalProductionReport::query()->where(['buyer_id' => $buyer_id, 'order_id' => $order_id])
                ->get();
        }
        $pdf = PDF::loadView(
            'manual-production::reports.rejection.style_wise_rejection_report_pdf',
            compact('reports', 'orders', 'order_id')
        )->setPaper('a4', 'landscape');
        return $pdf->stream('style_wise_rejection_report.pdf');
    }

    public function styleWiseRejectionReportExcel(Request $request): BinaryFileResponse
    {
        $buyer_id = $request->get('buyer_id') ?? null;
        $order_id = $request->get('order_id') ?? null;
        $orders = [];
        $reports = [];
        if ($order_id) {
            $orders = Order::query()->where('id', $order_id)->pluck('style_name', 'id');
            $reports = ManualTotalProductionReport::query()->where(['buyer_id' => $buyer_id, 'order_id' => $order_id])
                ->get();
        }
        return Excel::download(
            new StyleWiseRejectionReportExport($reports, $orders, $order_id),
            $orders[$order_id] . '_style_wise_rejection_report.xlsx'
        );
    }

    public function yearlyRejectionReport(Request $request)
    {
        $year = $request->get('year') ?? date('Y');
        $reports = [];
        if ($year) {
            $reports = RejectionService::yearlyRejection($year);
        }
        return view('manual-production::reports.rejection.yearly-rejection-report', compact('year', 'reports'));
    }

    public function yearlyRejectionReportPdf(Request $request)
    {
        $year = $request->get('year') ?? date('Y');
        $reports = [];
        if ($year) {
            $reports = RejectionService::yearlyRejection($year);
        }
        $pdf = PDF::loadView(
            'manual-production::reports.rejection.yearly_rejection_report_pdf',
            compact('reports', 'year')
        )->setPaper('a4', 'landscape');
        return $pdf->stream($year . '_yearly_rejection_report.pdf');
    }

    public function yearlyRejectionReportExcel(Request $request): BinaryFileResponse
    {
        $year = $request->get('year') ?? date('Y');
        $reports = [];
        if ($year) {
            $reports = RejectionService::yearlyRejection($year);
        }
        return Excel::download(
            new YearlyRejectionReportExport($reports, $year),
            $year . '_yearly_rejection_report.xlsx'
        );
    }
}
