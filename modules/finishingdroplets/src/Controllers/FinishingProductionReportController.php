<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Controllers;

use App\Http\Controllers\Controller;
use DB, Session, PDF, Excel;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\DailyFinishingProductionReportExport;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\DateWiseFinishingProductionSummaryReportExport;
use SkylarkSoft\GoRMG\Finishingdroplets\Exports\FinishingSummaryReportExport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;

class FinishingProductionReportController extends Controller
{

    public function dailyFinishingProductionReport(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $reports = $this->getdailyFinishingProductionReport($date);

        return view('finishingdroplets::reports.daily_finishing_production_report', [
            'reports' => $reports,
            'date' => $date,
        ]);
    }

    private function getdailyFinishingProductionReport($date)
    {
        return FinishingProductionReport::withoutGlobalScope('factoryId')
            ->with(['line' => function ($q) {
                $q->orderBy('sort', 'asc');
            }])
            ->select('finishing_production_reports.*', 'lines.sort as line_sort')
            ->join('lines', 'lines.id', 'finishing_production_reports.line_id')
            ->whereDate('finishing_production_reports.production_date', $date)
            ->where('finishing_production_reports.factory_id', factoryId())
            ->orderBy('finishing_production_reports.floor_id', 'desc')
            ->get()->filter(function ($value, $key) {
                return $value->sewing_input > 0 || $value->sewing_output > 0;
            });
    }

    public function dailyFinishingProductionReportDownload($type, $date)
    {
        $data['reports'] = $this->getdailyFinishingProductionReport($date);
        $data['date'] = $date;
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('finishingdroplets::reports.downloads.pdf.daily_finishing_production_report_download', $data, [], [
                'format' => 'A4-L'
            ]);
            return $pdf->download('Finishing Production Report.pdf');
        } else {
            return \Excel::download(new DailyFinishingProductionReportExport($data), 'Finishing Production Report.xlsx');
        }
    }

    public function dateWiseFinishingSummaryReport(Request $request)
    {
        $current_date = $request->current_date ?? date('Y-m-d');
        $buyer_id = $request->buyer_id ?? null;
        $order_id = $request->order_id ?? null;

        $buyers = $buyer_id ? Buyer::where('id', $buyer_id)->pluck('name', 'id') : [];
        $orders = $order_id ? Order::where('id', $order_id)->pluck('style_name', 'id') : [];

        $reports = $this->getDateWiseFinishingSummaryReportData($current_date, $buyer_id, $order_id);

        return view('finishingdroplets::reports.date_wise_finishing_summary_report', [
            'current_date' => $current_date,
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
            'buyers' => $buyers,
            'orders' => $orders,
            'reports' => $reports,
        ]);
    }

    private function getDateWiseFinishingSummaryReportData($current_date = '', $buyer_id = '', $order_id = '')
    {
        if ($current_date == '' || $buyer_id == '' || $order_id == '') {
            return null;
        }

        return DateAndColorWiseProduction::with('buyer:id,name', 'order', 'purchaseOrder', 'color')
            ->whereDate('production_date', '<=', $current_date)
            ->when($buyer_id != '', function ($query) use ($buyer_id) {
                return $query->where('buyer_id', $buyer_id);
            })
            ->when($order_id != '', function ($query) use ($order_id) {
                return $query->where('order_id', $order_id);
            })
            ->selectRaw("buyer_id, order_id, purchase_order_id, color_id,
             SUM(sewing_output_qty) as sewing_output_qty,
             SUM(poly_qty) as poly_qty,
             SUM(iron_qty) as iron_qty,
             SUM(packing_qty) as packing_qty,
             SUM(ship_qty) as ship_qty,
             SUM(iron_rejection_qty + poly_rejection + packing_rejection_qty) as total_rejection_qty
             ")
            ->where(function ($query) {
                $query->orWhere('poly_qty', '>', 0)
                    ->orWhere('sewing_output_qty', '>', 0)
                    ->orWhere('iron_qty', '>', 0)
                    ->orWhere('packing_qty', '>', 0)
                    ->orWhere('ship_qty', '>', 0);
            })
            ->groupBy('buyer_id', 'order_id', 'purchase_order_id', 'color_id')
            ->get();
    }

    public function dateWiseFinishingSummaryReportDownload(Request $request)
    {
        $type = $request->type;
        $current_date = $request->current_date ?? null;
        $buyer_id = $request->buyer_id ?? null;
        $order_id = $request->order_id ?? null;
        $data['current_date'] = $current_date;
        $data['reports'] = $this->getDateWiseFinishingSummaryReportData($current_date, $buyer_id, $order_id);
        if ($type == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView(
                    'finishingdroplets::reports.downloads.pdf.date_wise_finishing_summary_report_download',
                    $data,
                    [],
                    ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('finishing_production_summary_report' . date('d_m_Y') . '.pdf');
        } else {
            return Excel::download(new DateWiseFinishingProductionSummaryReportExport($data), 'finishing_production_summary_report' . date('d_m_Y') . '.xlsx');
        }
    }

    public function finishingSummaryReport(Request $request)
    {
        $buyer_id = $request->buyer_id ?? null;
        $order_id = $request->order_id ?? null;
        $current_date = date('Y-m-d');

        $buyers = $buyer_id ? Buyer::query()->where('id', $buyer_id)->pluck('name', 'id') : [];
        $orders = $order_id ? Order::query()->where('id', $order_id)->pluck('style_name', 'id') : [];

        $reports = $this->getFinishingSummaryReportData($buyer_id, $order_id);

        return view('finishingdroplets::reports.finishing_summary_report', [
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
            'buyers' => $buyers,
            'orders' => $orders,
            'current_date' => $current_date,
            'reports' => $reports ?? null,
        ]);
    }

    private function getFinishingSummaryReportData($buyer_id = null, $order_id = null)
    {
        if (!$buyer_id && !$order_id) {
            return null;
        }
        return DateAndColorWiseProduction::with('buyer:id,name', 'order', 'purchaseOrder', 'color')
            ->when(($buyer_id && $order_id == null), function ($query) use ($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when($order_id, function ($query) use ($order_id) {
                $query->where('order_id', $order_id);
            })
            ->selectRaw("buyer_id, order_id, purchase_order_id, color_id,
                 SUM(sewing_output_qty) as sewing_output_qty,
                 SUM(poly_qty) as poly_qty,
                 SUM(iron_qty) as iron_qty,
                 SUM(packing_qty) as packing_qty,
                 SUM(ship_qty) as ship_qty,
                 SUM(iron_rejection_qty + poly_rejection + packing_rejection_qty) as total_rejection_qty
             ")
            ->groupBy('buyer_id', 'order_id', 'purchase_order_id', 'color_id')
            ->get();
    }

    public function finishingSummaryReportDownload(Request $request)
    {
        $type = $request->type;
        $buyer_id = $request->buyer_id ?? null;
        $order_id = $request->order_id ?? null;
        $data['current_date'] = date('Y-m-d');
        $data['reports'] = $this->getFinishingSummaryReportData($buyer_id, $order_id);
        if ($type == 'pdf') {
            $pdf = PDF::loadView('finishingdroplets::reports.downloads.pdf.finishing_summary_report_download', $data);
            return $pdf->download('finishing_production_summary_report' . date('d_m_Y') . '.pdf');
        } else {
            return Excel::download(new FinishingSummaryReportExport($data), 'finishing_production_summary_report' . date('d_m_Y') . '.xlsx');
        }
    }

    public function styleWiseFinishingSummaryReport(Request $request)
    {
        $buyer_id = $request->buyer_id ?? null;
        $order_id = $request->order_id ?? null;
        $current_date = date('Y-m-d');

        $buyers = $buyer_id ? Buyer::where('id', $buyer_id)->pluck('name', 'id') : [];
        $orders = $order_id ? Order::where('id', $order_id)->pluck('style_name', 'id') : [];

        $reports = $this->getStyleWiseFinishingSummaryReportData($buyer_id, $order_id);

        return view('finishingdroplets::reports.style_wise_finishing_summary_report', [
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
            'buyers' => $buyers,
            'orders' => $orders,
            'current_date' => $current_date,
            'reports' => $reports ?? null,
        ]);
    }

    private function getStyleWiseFinishingSummaryReportData($buyer_id = null, $order_id = null)
    {
        if (!$buyer_id && !$order_id) {
            return null;
        }
        $date = \now()->subDays(366)->toDateString();

        return DateAndColorWiseProduction::query()
            ->with([
                'buyer:id,name', 
                'order:id,style_name'
            ])
            ->where('production_date', '>=', $date)
            ->when(($buyer_id && $order_id == null), function ($query) use ($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when($order_id, function ($query) use ($order_id) {
                $query->where('order_id', $order_id);
            })
            ->get();
    }
}
