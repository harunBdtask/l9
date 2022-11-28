<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use PDF, Excel;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\V2\DailyInputReportExport;

class DateWiseReportController extends Controller
{
    public function getDailyInputReport(Request $request) {
        $date = $request->date ?? date('Y-m-d');
        $reports = $this->getDailyInputData($date);

        return view('inputdroplets::reports.v2.daily_input_status_report', [
            'reports' => $reports,
            'date' => $date
        ]);
    }

    public function getDailyInputData($date)
    {
        return FinishingProductionReport::query()
            ->with([
                'buyer:id,name',
                'order:id,style_name',
                'purchaseOrder.purchaseOrderDetails',
                'color:id,name'
            ])
            ->groupBy('buyer_id', 'order_id', 'purchase_order_id', 'color_id')
            ->selectRaw('buyer_id, order_id, purchase_order_id, color_id, sum(sewing_input) as sewing_input')
            ->whereDate('production_date', $date)
            ->where('sewing_input', '>', 0)
            ->orderBy('buyer_id')
            ->get()
            ->map(function($report) {
                $orderQty = $report->purchaseOrder->purchaseOrderDetails->where('color_id', $report->color_id)->sum('quantity');
                $totalInput = TotalProductionReport::query()
                    ->where([
                        'purchase_order_id' => $report->purchase_order_id, 
                        'color_id' => $report->color_id
                    ])->sum('total_input');
                $inputPercent = $orderQty > 0 ? round(($totalInput * 100) / $orderQty) : 0;
                $balance = $orderQty - $totalInput;
                return [
                    'buyer_id' => $report->buyer_id,
                    'buyer' => $report->buyer,
                    'order_id' => $report->order_id,
                    'order' => $report->order,
                    'purchase_order_id' => $report->purchase_order_id,
                    'purchaseOrder' => $report->purchaseOrder,
                    'order_qty' => $orderQty,
                    'color_id' => $report->color_id,
                    'color' => $report->color,
                    'today_sewing_input' => $report->sewing_input,
                    'total_input' => $totalInput,
                    'input_percentage' => $inputPercent,
                    'balance' => $balance > 0 ? $balance : 0,
                ];
            });
    }

    public function getDailyInputReportDownload(Request $request)
    {
        $type = $request->type;
        $date = $request->date;
        $data['date'] = $date;
        $data['reports'] = $this->getDailyInputData($date);

        if ($type == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)
                ->loadView('inputdroplets::reports.downloads.pdf.v2.daily_input_status_report_download', $data)
                ->setPaper('a4')
                ->setOrientation('landscape')
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('daily_input_report_'.date('d_m_Y', strtotime($date)).'.pdf');
        } else {
            return Excel::download(new DailyInputReportExport($data), 'daily_input_report_'.date('d_m_Y', strtotime($date)).'.xlsx');
        }
    }
}