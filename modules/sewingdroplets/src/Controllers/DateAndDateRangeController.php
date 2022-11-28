<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inputdroplets\Models\DateWiseSewingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\DailyInputOutputReportExport;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\DateRangeWiseOutputReportExport;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\LineWiseSewingAvgReportExport;
use SkylarkSoft\GoRMG\Sewingdroplets\Exports\MonthlyLineWiseProductionSummaryReportExport;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use Carbon\Carbon;
use DB;

class DateAndDateRangeController extends Controller
{

    public function getDateRangeWiseReportForm()
    {
        $date = date('Y-m-d');
        $floors = Floor::pluck('floor_no', 'id')->prepend('All Floor', 'all');
        $data = $this->getDateRangeWiseReport($date, $date, 'all');
        $data['floors'] = $floors;
        $data['lines'] = ['' => 'Select Line'];

        return view('sewingdroplets::reports.date-range-wise-report', $data);
    }

    public function getDateRangeWiseReportPost(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date|before_or_equal:to_date',
            'to_date' => 'required|date|after_or_equal:from_date'
        ]);
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $floor_id = $request->floor_id ?? 'all';
        $line_id = $request->line_id ?? null;

        $frmDate = Carbon::parse($from_date);
        $toDate = Carbon::parse($to_date);
        $diff = $frmDate->diffInDays($toDate);

        if ($diff > 31) {
            \Session::flash('error', 'Please enter maximum one month date range');
            return redirect('date-wise-sewing-output');
        }
        $floors = Floor::pluck('floor_no', 'id')->prepend('All Floor', 'all');
        $lines = ['' => 'Select Line'];
        if ($floor_id != 'all') {
            $lines = Line::where('floor_id', $floor_id)->pluck('line_no', 'id')->prepend('Select Line', '');
        }
        $data = $this->getDateRangeWiseReport($from_date, $to_date, $floor_id, $line_id);
        $data['floors'] = $floors;
        $data['lines'] = $lines;
        return view('sewingdroplets::reports.date-range-wise-report', $data);
    }

    public function getDateRangeWiseReport($from_date, $to_date, $floor_id, $line_id = '')
    {
        $line_wise_summary_report = null;
        $sewing_output_summary = null;
        $dateWiseSewingReportQuery = DateWiseSewingProductionReport::when(
            $floor_id != 'all',
            function ($query) use ($floor_id) {
                $query->where('floor_id', $floor_id);
            }
        )
            ->when($line_id != '', function ($query) use ($line_id) {
                $query->where('line_id', $line_id);
            })
            ->whereDate('sewing_date', '>=', $from_date)
            ->whereDate('sewing_date', '<=', $to_date)
            ->orderBy('line_id', 'asc')
            ->get();
        if ($dateWiseSewingReportQuery) {
            // Line Wise Summary Report
            $line_wise_summary_report = [];
            $i = 0;
            foreach ($dateWiseSewingReportQuery->groupBy('floor_id') as $floorGroup) {
                foreach ($floorGroup->groupBy('line_id') as $lineGroup) {
                    $line_wise_summary_report[$i]['floor_no'] = $floorGroup->first()->floors->floor_no ?? 'N/A';
                    $line_wise_summary_report[$i]['line_no'] = $lineGroup->first()->lines->line_no ?? 'N/A';
                    $line_wise_summary_report[$i]['sewing_output'] = $lineGroup->sum('total_sewing_output');
                    $line_wise_summary_report[$i]['sewing_rejection'] = $lineGroup->sum('total_sewing_rejection');
                    $i++;
                }
            }

            // Sewing Output Summary Report
            $sewing_output_summary = [];
            $j = 0;
            foreach ($dateWiseSewingReportQuery as $floorKey => $group) {
                foreach ($group->sewing_details as $order) {
                    $getOrder = DateWiseSewingProductionReport::getPurchaseOrder($order['purchase_order_id']);
                    $getColor = DateWiseSewingProductionReport::getColor($order['color_id']);

                    $sewing_output_summary[$j]['floor_id'] = $group->floor_id;
                    $sewing_output_summary[$j]['floor_no'] = $group->floors->floor_no;
                    $sewing_output_summary[$j]['line_id'] = $group->line_id;
                    $sewing_output_summary[$j]['line_no'] = $group->lines->line_no;
                    $sewing_output_summary[$j]['purchase_order_id'] = $getOrder->id ?? null;
                    $sewing_output_summary[$j]['color_id'] = $getColor->id;
                    $sewing_output_summary[$j]['buyer_name'] = $getOrder->buyer->name ?? 'Buyer';
                    $sewing_output_summary[$j]['style_name'] = $getOrder->order->style_name ?? 'Order/Style';
                    $sewing_output_summary[$j]['order_no'] = $getOrder->po_no ?? '';
                    $sewing_output_summary[$j]['color'] = $getColor->name ?? 'Color';
                    $sewing_output_summary[$j]['sewing_output'] = $order['sewing_output'] ?? 0;
                    $sewing_output_summary[$j]['sewing_rejection'] = $order['sewing_rejection'] ?? 0;
                    $j++;
                }
            }
        }

        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['floor_id'] = $floor_id;
        $data['line_id'] = $line_id;
        $data['line_wise_summary_report'] = $line_wise_summary_report;
        $data['sewing_output_summary'] = collect($sewing_output_summary);

        $data['section_1_count'] = collect($sewing_output_summary)->groupBy('line_id')->count();
        $data['section_2_count'] = collect($line_wise_summary_report)->count();
        $data['section_3_count'] = collect($sewing_output_summary)->groupBy('purchase_order_id')->count();
        $data['section_4_count'] = 0;
        foreach ($data['sewing_output_summary']->groupBy('order_id') as $groupByOrder) {
            foreach ($groupByOrder->groupBy('color_id') as $groupByColor) {
                $data['section_4_count'] = $groupByOrder->groupBy('color_id')->count();
            }
        }
        return $data;
    }

    public function getDateRangeWiseReportDownload(Request $request)
    {
        $type = $request->type;
        $floor_id = $request->floor_id;
        $line_id = $request->line_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $data = $this->getDateRangeWiseReport($from_date, $to_date, $floor_id, $line_id);

        if ($type == 'pdf') {
            $pdf = \PDF::setOption('enable-local-file-access', true)
                ->loadView('sewingdroplets::reports.downloads.pdf.date-range-wise-sewing-report-download', $data)
                ->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);

            return $pdf->stream('date-wise-report.pdf');
        } else {
            return \Excel::download(new DateRangeWiseOutputReportExport($data), 'date-wise-output-report.xlsx');
        }
    }

    public function getLineDateWiseAvgForm()
    {
        return view('sewingdroplets::reports.line-date-wise-avg-report');
    }

    public function getLineDateWiseAvgReport(Request $request)
    {
        try {
            $order_id = $request->order_id ?? null;
            $purchase_order_id = $request->purchase_order_id ?? null;
            $reports = $this->getLineDateWiseAvgReportData($order_id, $purchase_order_id);

            $html = view('sewingdroplets::reports.tables.line-date-wise-avg-report-table', ['reports' => $reports])->render();
            return response()->json([
                'status' => 'success',
                'html' => $html,
                'message' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'html' => null,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function getLineDateWiseAvgReportData($order_id, $purchase_order_id = null)
    {
        return FinishingProductionReport::query()
            ->when($order_id && !$purchase_order_id, function ($query) use ($order_id) {
                $query->where('order_id', $order_id);
            })
            ->when($purchase_order_id, function ($query) use ($purchase_order_id) {
                $query->where('purchase_order_id', $purchase_order_id);
            })
            ->get()
            ->filter(function ($item) {
                return ($item->sewing_input > 0 || $item->sewing_output > 0);
            });
    }

    public function getLineDateWiseAvgReportDownload($type, $order_id, $purchase_order_id = '')
    {
        try {
            $data['reports'] = $this->getLineDateWiseAvgReportData($order_id, $purchase_order_id);
            if ($type == 'pdf') {
                $pdf = \PDF::setOption('enable-local-file-access', true)
                    ->loadView('sewingdroplets::reports.downloads.pdf.line-date-wise-output-avg-report-download', $data)
                    ->setpaper('a4')->setOrientation('landscape')->setOptions([
                        'header-html' => view('skeleton::pdf.header'),
                        'footer-html' => view('skeleton::pdf.footer'),
                    ]);

                return $pdf->stream('line-date-wise-output-avg-report.pdf');
            } else {
                return \Excel::download(new LineWiseSewingAvgReportExport($data), 'line-date-wise-output-avg-report.xlsx');
            }
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function dailyInputOutputReport(Request $request)
    {
        $buyer_id = $request->buyer_id ?? null;
        $order_id = $request->order_id ?? null;
        $buyers = $buyer_id ? Buyer::where('id', $buyer_id)->pluck('name', 'id') : [];
        $orders = $order_id ? Order::where('id', $order_id)->pluck('style_name', 'id') : [];
        $reports = $buyer_id && $order_id ? $this->getDailyInputOutputReport($buyer_id, $order_id) : null;

        return view('sewingdroplets::reports.daily_input_output_report', [
            'buyers' => $buyers,
            'orders' => $orders,
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
            'reports' => $reports,
        ]);
    }

    private function getDailyInputOutputReport($buyer_id, $order_id = null)
    {
        return FinishingProductionReport::query()
            ->when($buyer_id && !$order_id, function ($query) use($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when($order_id, function ($query) use($order_id) {
                $query->where('order_id', $order_id);
            })
            ->selectRaw('production_date, SUM(sewing_input) as sewing_input_sum, SUM(sewing_output) as sewing_output_sum')
            ->groupBy('buyer_id', 'order_id', 'production_date')
            ->get()
            ->filter(function ($item) {
                return ($item->sewing_input_sum > 0 || $item->sewing_output_sum > 0);
            });
    }

    public function dailyInputOutputReportDownload($type, $buyer_id, $order_id)
    {
        try {
            $data['buyer_id'] = $buyer_id;
            $data['order_id'] = $order_id;
            $buyers = Buyer::pluck('name', 'id');
            $orders_query = Order::where('buyer_id', $buyer_id)->get();
            $orders = $orders_query->pluck('style_name', 'id');
            $data['buyers'] = $buyers;
            $data['orders'] = $orders;
            $data['reports'] = $buyer_id && $order_id ? $this->getDailyInputOutputReport($buyer_id, $order_id) : null;
            if ($type == 'pdf') {
                $pdf = \PDF::setOption('enable-local-file-access', true)
                    ->loadView('sewingdroplets::reports.downloads.pdf.daily_input_output_report_download', $data)
                    ->setOptions([
                        'header-html' => view('skeleton::pdf.header'),
                        'footer-html' => view('skeleton::pdf.footer'),
                    ]);

                return $pdf->stream('daily-input-output-report-' . $orders[$order_id] . '.pdf');
            } else {
                return \Excel::download(new DailyInputOutputReportExport($data), 'daily-input-output-report.xlsx');
            }
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function monthlyLineWiseProductionSummaryReport(Request $request)
    {
        $floors = Floor::all()->pluck('floor_no', 'id');
        $lines = [];
        $year = $request->year ?? (int)date('Y');
        $month = $request->month ?? (int)date('m');
        $floor_id = $request->floor_id ?? null;
        $line_id = $request->line_id ?? null;
        $reports = null;

        if ($floor_id && $year && $month) {
            $lines = Line::where('floor_id', $floor_id)->get()->pluck('line_no', 'id');
            $reports = $this->getMonthlyLineWiseProductionSummaryReport($floor_id, $year, $month, $line_id);
        }

        return view('sewingdroplets::reports.monthly_line_wise_production_summary', [
            'floors' => $floors,
            'lines' => $lines,
            'year' => $year,
            'month' => $month,
            'floor_id' => $floor_id,
            'line_id' => $line_id,
            'reports' => $reports,
        ]);
    }

    private function getMonthlyLineWiseProductionSummaryReport($floor_id, $year, $month, $line_id = '')
    {
        return FinishingProductionReport::whereYear('production_date', $year)
            ->whereMonth('production_date', $month)
            ->whereDate('production_date', '<=', date('Y-m-d'))
            ->where('floor_id', $floor_id)
            ->when($line_id != '', function ($query) use ($line_id) {
                return $query->where('line_id', $line_id);
            })
            ->selectRaw('production_date, buyer_id, order_id, purchase_order_id, SUM(sewing_input) as sewing_input_sum, SUM(sewing_output) as sewing_output_sum')
            ->groupBy('production_date', 'buyer_id', 'order_id', 'purchase_order_id')
            ->orderBy('production_date')
            ->orderBy('buyer_id')
            ->orderBy('order_id')
            ->orderBy('purchase_order_id')
            ->get()
            ->filter(function ($item, $key) {
                return $item->sewing_input_sum > 0 || $item->sewing_output_sum > 0;
            });
    }

    public function monthlyLineWiseProductionSummaryReportDownload(Request $request)
    {
        try {
            $type = $request->type;
            $floor_id = $request->floor_id;
            $line_id = $request->line_id;
            $year = $request->year;
            $month = $request->month;
            $data['reports'] = $this->getMonthlyLineWiseProductionSummaryReport($floor_id, $year, $month, $line_id);
            $data['floor_id'] = $floor_id;
            $data['line_id'] = $line_id;
            $data['floor_no'] = isset($floor_id) ? Floor::findOrFail($floor_id)->floor_no : null;
            $data['line_no'] = isset($line_id) ? Line::findOrFail($line_id)->line_no : null;

            $data['year'] = $year;
            $data['month'] = $month;
            if ($type == 'pdf') {
                $pdf = \PDF::setOption('enable-local-file-access', true)
                    ->loadView('sewingdroplets::reports.downloads.pdf.monthly_line_wise_production_summary_report_download',
                        $data, [], ['format' => 'A4-L']
                    )->setOptions([
                        'header-html' => view('skeleton::pdf.header'),
                        'footer-html' => view('skeleton::pdf.footer'),
                    ]);

                return $pdf->stream('monthly-line-wise-production-summary-report.pdf');
            } else {
                return \Excel::download(new MonthlyLineWiseProductionSummaryReportExport($data), 'monthly-line-wise-production-summary-report.xlsx');
            }
        } catch (\Exception $e) {
            return redirect('/monthly-line-wise-production-summary-report');
        }
    }
}
