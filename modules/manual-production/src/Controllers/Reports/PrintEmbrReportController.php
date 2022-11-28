<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Reports;

use App\Http\Controllers\Controller;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;
use SkylarkSoft\GoRMG\ManualProduction\Exports\ChallanWiseEmbrPrintExport;
use SkylarkSoft\GoRMG\ManualProduction\Exports\PrintEmberExport;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWisePrintEmbrReport;
use SkylarkSoft\GoRMG\ManualProduction\Rules\MaxOneMonthRule;
use SkylarkSoft\GoRMG\ManualProduction\Services\PrintEmbrReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PrintEmbrReportController extends Controller
{
    public function dateWisePrintEmbrReport(Request $request)
    {
        $request->validate([
            'date_from' => ['nullable', 'date', 'before_or_equal:date_to'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from', new MaxOneMonthRule],
        ]);
        $date_from = $request->date_from ?? \now()->startOfMonth()->toDateString();
        $date_to = $request->date_to ?? \now()->toDateString();
        $data = $this->data($date_from, $date_to);
        return view('manual-production::reports.print_ember_report.print_ember_report', compact('data', 'date_from', 'date_to'));
    }

    public function dateWisePrintEmbrReportPdf(Request $request)
    {
        $date_from = $request->date_from ?? \now()->startOfMonth()->toDateString();
        $date_to = $request->date_to ?? \now()->toDateString();
        $data = $this->data($date_from, $date_to);
        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('manual-production::reports.print_ember_report.print_ember_report_pdf', compact('data'))
            ->setPaper('A4')->setOrientation('landscape');

        return $pdf->stream("print_ember_report.pdf");
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function dateWisePrintEmbrReportExcel(Request $request): BinaryFileResponse
    {
        $date_from = $request->date_from ?? \now()->startOfMonth()->toDateString();
        $date_to = $request->date_to ?? \now()->toDateString();
        return Excel::download(new PrintEmberExport($date_from, $date_to), 'print_ember.xlsx');
    }

    private function data($date_from, $date_to)
    {
        return ManualDateWisePrintEmbrReport::query()
            ->with('factory', 'buyer', 'order', 'purchaseOrder', 'color')
            ->whereDate('production_date', '>=', $date_from)
            ->whereDate('production_date', '=', $date_to)
            ->get();
    }

    public function challanWiseEmbrReport(Request $request)
    {
        $buyers = Buyer::query()->where('factory_id', factoryId())->pluck('name as text', 'id');
        $buyer_id = $request->input('buyer_id');
        $order_id = $request->input('order_id');
        $color_id = $request->input('color_id');
        $order = null;
        $reports = [];
        $orders = [];
        $colors = $color_id ? Color::query()->where('id', $color_id)->pluck('name', 'id') : [];
        if ($order_id) {
            list($order, $orders, $reports) = PrintEmbrReportService::challanWiseEmbr($color_id, $order_id);
        }
        return view('manual-production::reports.print.challan_wise_embr_report', compact(
            'buyers',
            'buyer_id',
            'order_id',
            'orders',
            'order',
            'color_id',
            'colors',
            'reports'
        ));
    }

    public function challanWisePrintReport(Request $request)
    {
        $buyers = Buyer::query()->where('factory_id', factoryId())->pluck('name as text', 'id');
        $buyer_id = $request->input('buyer_id');
        $order_id = $request->input('order_id');
        $color_id = $request->input('color_id');
        $order = null;
        $reports = [];
        $orders = [];
        $colors = $color_id ? Color::query()->where('id', $color_id)->pluck('name', 'id') : [];
        if ($order_id) {
            list($order, $orders, $reports) = PrintEmbrReportService::challanWisePrint($color_id, $order_id);
        }
        return view('manual-production::reports.print.challan_wise_print_report', compact(
            'buyers',
            'buyer_id',
            'order_id',
            'orders',
            'order',
            'color_id',
            'colors',
            'reports'
        ));
    }

    public function challanWiseEmbrReportPdf(Request $request)
    {
        $order_id = $request->input('order_id');
        $color_id = $request->input('color_id');
        $order = null;
        $reports = [];
        $orders = [];
        if ($order_id) {
            list($order, $orders, $reports) = PrintEmbrReportService::challanWiseEmbr($color_id, $order_id);
        }
        $pdf = PDF::loadView('manual-production::reports.print.challan_wise_embr_report_pdf',
            compact('order', 'orders', 'reports'));
        $pdf->setPaper('A4')->setOrientation('landscape');
        return $pdf->stream("challan_wise_print_ember_report.pdf");
    }

    public function challanWiseEmbrReportExcel(Request $request): BinaryFileResponse
    {
        $order_id = $request->input('order_id');
        $color_id = $request->input('color_id');
        $order = null;
        $reports = [];
        $orders = [];
        if ($order_id) {
            list($order, $orders, $reports) = PrintEmbrReportService::challanWiseEmbr($color_id, $order_id);
        }
        return Excel::download(new ChallanWiseEmbrPrintExport($order, $orders, $reports, 'embr'), 'challan_wise_ember.xlsx');
    }

    public function challanWisePrintReportPdf(Request $request)
    {
        $order_id = $request->input('order_id');
        $color_id = $request->input('color_id');
        $order = null;
        $reports = [];
        $orders = [];
        if ($order_id) {
            list($order, $orders, $reports) = PrintEmbrReportService::challanWisePrint($color_id, $order_id);
        }
        $pdf = PDF::loadView('manual-production::reports.print.challan_wise_print_report_pdf',
            compact('order', 'orders', 'reports'));
        $pdf->setPaper('A4')->setOrientation('landscape');
        return $pdf->stream("challan_wise_print_report.pdf");
    }

    public function challanWisePrintReportExcel(Request $request): BinaryFileResponse
    {
        $order_id = $request->input('order_id');
        $color_id = $request->input('color_id');
        $order = null;
        $reports = [];
        $orders = [];
        if ($order_id) {
            list($order, $orders, $reports) = PrintEmbrReportService::challanWisePrint($color_id, $order_id);
        }
        return Excel::download(new ChallanWiseEmbrPrintExport($order, $orders, $reports, 'print'), 'challan_wise_print.xlsx');
    }
}
