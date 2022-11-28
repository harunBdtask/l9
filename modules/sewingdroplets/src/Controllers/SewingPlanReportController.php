<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB, Session, Exception, PDF, Excel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\SewingLinePlanReportExport;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingPlan;

class SewingPlanReportController extends Controller
{
    public function sewingLinePlanReport(Request $request)
    {
        $buyers = Buyer::pluck('name', 'id');
        $orders = [];
        $floors = Floor::pluck('floor_no', 'id');
        $lines = [];
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        $buyer_id = $request->buyer_id ?? null;
        $order_id = $request->order_id ?? null;
        $floor_id = $request->floor_id ?? null;
        $line_id = $request->line_id ?? null;
        if ($buyer_id) {
            $orders = Order::where('buyer_id', $buyer_id)->pluck('style_name', 'id');
        }
        if ($floor_id) {
            $lines = Line::where('floor_id', $floor_id)->pluck('line_no', 'id');
        }

        $reports = $this->getSewingLinePlanReportData($from_date, $to_date, $buyer_id, $order_id, $floor_id, $line_id);

        return view('sewingdroplets::reports.sewing_line_plan_report', [
            'buyers' => $buyers,
            'orders' => $orders,
            'floors' => $floors,
            'lines' => $lines,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'reports' => $reports,
        ]);
    }

    private function getSewingLinePlanReportData($form_date = '', $to_date = '', $buyer_id = '', $order_id = '', $floor_id = '', $line_id = '')
    {
        if ($form_date == '' || $to_date == '') {
            return null;
        }
        return SewingPlan::with('sewingPlanDetails')
            ->whereDate('start_date', '>=', $form_date)
            ->whereDate('start_date', '<=', $to_date)
            ->when($buyer_id != '', function ($query) use($buyer_id) {
                return $query->where('buyer_id', $buyer_id);
            })
            ->when($order_id != '', function ($query) use($order_id) {
                return $query->where('order_id', $order_id);
            })
            ->when($floor_id != '', function ($query) use($floor_id) {
                return $query->where('floor_id', $floor_id);
            })
            ->when($line_id != '', function ($query) use($line_id) {
                return $query->where('line_id', $line_id);
            })->get();
    }

    public function sewingLinePlanReportDownload(Request $request)
    {
        try {
            $type = $request->type;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $buyer_id = $request->buyer_id ?? null;
            $order_id = $request->order_id ?? null;
            $floor_id = $request->floor_id ?? null;
            $line_id = $request->line_id ?? null;
            $data['reports'] = $this->getSewingLinePlanReportData($from_date, $to_date, $buyer_id, $order_id, $floor_id, $line_id);
            if($type == 'pdf') {
                $pdf = PDF::loadView('sewingdroplets::reports.downloads.pdf.sewing_line_plan_report_download', $data);
                return $pdf->download('sewing_line_plan_report'. date('d_m_Y').'.pdf');
            } else {
                return Excel::download(new SewingLinePlanReportExport($data), 'sewing_line_plan_report'. date('d_m_Y').'.xlsx');
            }
        } catch (Exception $e) {
            return redirect()->back();
        }
    }
}