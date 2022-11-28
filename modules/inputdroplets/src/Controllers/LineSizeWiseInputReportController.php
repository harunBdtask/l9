<?php

namespace SkylarkSoft\GoRMG\Inputdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inputdroplets\Models\LineSizeWiseSewingReport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Excel;
use SkylarkSoft\GoRMG\Inputdroplets\Exports\LineSizeWiseReportExport;

class LineSizeWiseInputReportController extends Controller
{
    public function index(Request $request)
    {
        $orderId = $request->get('order_id') ?? null;
        $orders = $orderId ? Order::query()->where('id', $orderId)->pluck('style_name', 'id') : [];
        $date = $request->get('date') ?? null;

        $reports = $this->fetchReportData($date, $orderId);
        $size_ids = $reports && $reports->count() ? $reports->sortBy('size_id')->pluck('size_id')->unique()->flatten()->toArray() : [];

        return view('inputdroplets::reports.line_size_wise_input_report', [
            'order_id' => $orderId,
            'orders' => $orders,
            'date' => $date,
            'reports' => $reports,
            'size_ids' => $size_ids,
        ]);
    }

    private function fetchReportData($date = null, $order_id = null)
    {
        if (!$date && !$order_id) {
            return null;
        }
        return LineSizeWiseSewingReport::query()
            ->where('sewing_input', '>', 0)
            ->when($order_id, function ($query) use ($order_id) {
                return $query->where('order_id', $order_id);
            })
            ->when($date, function ($query) use($date) {
                return $query->where('production_date', $date);
            })
            ->get();
    }

    public function download(Request $request)
    {
        $type = $request->get('type');
        $orderId = $request->get('order_id') ?? null;
        $date = $request->get('date') ?? null;

        $reports = $this->fetchReportData($date, $orderId);
        $size_ids = $reports && $reports->count() ? $reports->sortBy('size_id')->pluck('size_id')->unique()->flatten()->toArray() : [];
        $data = [
            'order_id' => $orderId,
            'date' => $date,
            'reports' => $reports,
            'size_ids' => $size_ids,
        ];
        if ($type == 'xls') {
            return Excel::download(new LineSizeWiseReportExport($data), 'line-size-wise-input-report'.date('d-m-Y').'.xlsx');
        } else {
            \redirect()->back();
        }
    }

}
