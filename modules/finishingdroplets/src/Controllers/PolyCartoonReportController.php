<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\AllOrdersPolyReportExport;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\DateWisePolyCartoonReportExport;

class PolyCartoonReportController extends Controller
{
    public function getAllOrdersReport()
    {
        $order_wise_report = $this->getAllOrdersReportData(PAGINATION);

        return view('finishingdroplets::reports.order_wise_poly_cartoon_summary', [
            'order_wise_report' => $order_wise_report
        ]);
    }

    public function getAllOrdersReportData($pagination)
    {
        return TotalProductionReport::with('order', 'purchaseOrder')
            ->orderBy('buyer_id', 'desc')
            ->paginate($pagination);
    }

    public function getAllOrdersReportDownload($type)
    {
        $data['order_wise_report'] = $this->getAllOrdersReportData(PAGINATION);
        $data['download'] = 1;
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('finishingdroplets::reports.downloads.pdf.all-orders-poly-cartoon-report-download',
                    $data, [], ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('date-wise-finishing-report.pdf');
        } else {
            return \Excel::download(new AllOrdersPolyReportExport(), 'all-orders-poly-report.xlsx');
        }
    }

    public function dateWiseIronPolyPackingSummary(Request $request)
    {
        $date = date('Y-m-d');
        $from_date = $request->from_date ?? $date;
        $to_date = $request->to_date ?? $date;

        $frmDate = Carbon::parse($from_date);
        $toDate = Carbon::parse($to_date);
        $diff = $frmDate->diffInDays($toDate);

        if ($diff > 31) {
            Session::flash('error', 'Please enter maximum one month date range');
            return redirect()->back();
        }

        $reports = $this->getDateWiseIronPolyPackingSummary($from_date, $to_date);

        return view('finishingdroplets::reports.date_wise_iron_poly_packing_summary', [
            'from_date' => $request->from_date ?? $date,
            'to_date' => $request->to_date ?? $date,
            'reports' => $reports
        ]);
    }

    public function getDateWiseIronPolyPackingSummary($from_date, $to_date)
    {
        return DateAndColorWiseProduction::with([
            'buyer:id,name',
            'order:id,style_name',
            'purchaseOrder:id,po_no',
            'color:id,name'
        ])
            ->whereDate('production_date', '>=', $from_date)
            ->whereDate('production_date', '<=', $to_date)
            ->get();

        /*return DateAndColorWiseProduction::where('production_date', '>=', $from_date)
            ->where('production_date', '<=', $to_date)
            ->selectRaw('
                buyers.name as buyer_name,
                orders.booking_no as booking_no,
                purchase_orders.po_no as po_no,
                SUM(date_and_color_wise_productions.iron_qty) as iron_qty,
                SUM(date_and_color_wise_productions.iron_rejection_qty) as iron_rejection_qty,
                SUM(date_and_color_wise_productions.poly_qty) as poly_qty,
                SUM(date_and_color_wise_productions.poly_rejection) as poly_rejection,
                SUM(date_and_color_wise_productions.packing_qty) as packing_qty,
                SUM(date_and_color_wise_productions.packing_rejection_qty) as packing_rejection_qty
            ')
            ->join('buyers', 'buyers.id', 'date_and_color_wise_productions.buyer_id')
            ->join('orders', 'orders.id', 'date_and_color_wise_productions.order_id')
            ->join('purchase_orders', 'purchase_orders.id', 'date_and_color_wise_productions.purchase_order_id')
            ->groupBy('buyer_name','booking_no','po_no')
            ->get();*/
    }

    public function dateWiseIronPolyPackingSummaryReportDownload($type, $from_date, $to_date)
    {
        $reports = $this->getDateWiseIronPolyPackingSummary($from_date, $to_date);
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('finishingdroplets::reports.downloads.pdf.date_wise_iron_poly_packing_summary_report_download',
                    compact('reports', 'from_date', 'to_date'), [], ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('date-wise-poly-cartoon-report.pdf');
        } else {
            return \Excel::download(new DateWisePolyCartoonReportExport($reports, $type, $from_date, $to_date), 'date-wise-poly-cartoon-report.xlsx');
        }
    }

}
