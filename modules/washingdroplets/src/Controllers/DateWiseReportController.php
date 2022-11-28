<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Washingdroplets\Exports\DateWiseWashingReportExport;
use SkylarkSoft\GoRMG\Washingdroplets\Exports\MonthWiseWashingReportExport;
use SkylarkSoft\GoRMG\Washingdroplets\Models\DateWiseWashingProductionReport;

class DateWiseReportController extends Controller
{
    public function dateWiseWashingReport(Request $request)
    {
        $from_date = $request->from_date ?? date('Y-m-d');

        $data['from_date'] = $from_date;

        $report = $this->getDateWiseColorWiseWashReportData($from_date, $from_date);
        return view('washingdroplets::reports.date_wise_washing_report', $data, $report);
    }

    public function getDateWiseColorWiseWashReportData($from_date, $to_date)
    {
        $date_wise_washing_report_query = DateWiseWashingProductionReport::where('washing_date', '>=', $from_date)
            ->where('washing_date', '<=', $to_date)->get();

        $color_wise_washing_summary_report = [];
        $i = 0;
        foreach ($date_wise_washing_report_query as $group) {
            foreach ($group->washing_details as $order) {
                $getPurchaseOrder = DateWiseWashingProductionReport::getPurchaseOrder($order['purchase_order_id']);
                $getColor = DateWiseWashingProductionReport::getColor($order['color_id']);
                if (!$getPurchaseOrder || !$getColor) {
                    continue;
                }
                $color_wise_washing_summary_report[$i]['orderKey'] = $i;
                $color_wise_washing_summary_report[$i]['purchase_order_id'] = $getPurchaseOrder->id;
                $color_wise_washing_summary_report[$i]['color_id'] = $getColor->id;
                $color_wise_washing_summary_report[$i]['buyer_name'] = $getPurchaseOrder->buyer->name ?? 'Buyer';
                $color_wise_washing_summary_report[$i]['order_style_no'] = $getPurchaseOrder->order->order_style_no ?? '';
                $color_wise_washing_summary_report[$i]['po_no'] = $getPurchaseOrder->po_no ?? '';
                $color_wise_washing_summary_report[$i]['color'] = $getColor->name ?? 'Color';
                $color_wise_washing_summary_report[$i]['total_wash_sent'] = $order['washing_sent'] ?? 0;
                $color_wise_washing_summary_report[$i]['total_wash_received'] = $order['washing_received'] ?? 0;
                $color_wise_washing_summary_report[$i]['total_wash_rejection'] = $order['washing_rejection'] ?? 0;
                $i++;
            }
        }

        $data['grand_total_sent'] = DateWiseWashingProductionReport::where('washing_date', '>=', $from_date)
            ->where('washing_date', '<=', $to_date)
            ->sum('total_washing_sent');
        $data['grand_total_received'] = DateWiseWashingProductionReport::where('washing_date', '>=', $from_date)
            ->where('washing_date', '<=', $to_date)
            ->sum('total_washing_received');
        $data['grand_total_rejected'] = DateWiseWashingProductionReport::where('washing_date', '>=', $from_date)
            ->where('washing_date', '<=', $to_date)
            ->sum('total_washing_rejection');
        $data['washing_report'] = collect($color_wise_washing_summary_report);
        return $data;
    }

    public function dateWiseWashingReportDownload($type, $date)
    {
        $washing_report = $this->getDateWiseColorWiseWashReportData($date, $date);
        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('washingdroplets::reports.downloads.pdf.date-wise-washing-report-download',
                    $washing_report, [], ['format' => 'A4-L']
                )->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('date-wise-wash-report.pdf');
        } else {
            return \Excel::download(new DateWiseWashingReportExport($washing_report), 'date-wise-wash-report.xlsx');

            /*\Excel::create('Date Wise Washing Report ', function ($excel) use ($washing_report) {
                $excel->sheet('Date Wise Washing Report', function ($sheet) use ($washing_report) {
                    $sheet->loadView('washingdroplets::reports.downloads.excels.date-wise-washing-report-download', $washing_report);
                });
            })->export('xls');*/
        }
    }

    public function monthWiseWashingReport(Request $request)
    {
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        if ($request->isMethod('post')) {
            $request->validate([
                'from_date' => 'required|date|date_format:Y-m-d|before_or_equal:to_date',
                'to_date' => 'required|date|date_format:Y-m-d|after_or_equal:from_date'
            ]);
            $from_date = $request->from_date;
            $to_date = $request->to_date;
        }
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $report = $this->getDateWiseColorWiseWashReportData($from_date, $to_date);
        return view('washingdroplets::reports.month_wise_washing_report', $data, $report);
    }

    public function monthWiseWashingReportDownload($type, $from_date, $to_date)
    {
        $data = $this->getDateWiseColorWiseWashReportData($from_date, $to_date);
        if ($type == 'pdf') {
            $pdf = \PDF::loadView('washingdroplets::reports.downloads.pdf.date-wise-washing-report-download', $data, [], [
                'format' => 'A4-L'
            ]);
            return $pdf->download('month-wise-wash-report.pdf');
        } else {
            return \Excel::download(new MonthWiseWashingReportExport($data), 'month-wise-wash-report.xlsx');

            /*\Excel::create('Month Wise Washing Report ', function ($excel) use ($data) {
                $excel->sheet('Month Wise Washing Report', function ($sheet) use ($data) {
                    $sheet->loadView('washingdroplets::reports.downloads.excels.date-wise-washing-report-download', $data);
                });
            })->export('xls');*/
        }
    }
}
