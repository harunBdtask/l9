<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\AccountingRealization;

use Excel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\BasicFinance\Models\AccountRealization;
use SkylarkSoft\GoRMG\BasicFinance\Services\FetchLeafNodesService;
use SkylarkSoft\GoRMG\BasicFinance\Exports\RealizationMisReportExcel;
use SkylarkSoft\GoRMG\BasicFinance\Requests\AccountingRealizationRequest;
use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\RealizationReportService;

class RealizationReportController extends Controller
{
    public function misReport(Request $request)
    {
        $start_date = $request->get('start_date')?date('Y-m-d', strtotime($request->get('start_date'))):date('Y-m-d');
        $end_date = $request->get('end_date')?date('Y-m-d', strtotime($request->get('end_date'))):date('Y-m-d');

        $listData = AccountRealization::query()
            ->with(['currency'])
            ->where(function($q) use($start_date, $end_date){
                return $q->whereBetween('realization_date', [$start_date, $end_date]);
            })
            ->get();
        $lists = RealizationReportService::getReport($listData);

        return view("basic-finance::accounting-realization.reports.mis-report", [
            'lists' => $lists,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function misReportExcel(Request $request)
    {
        $start_date = $request->get('start_date')?date('Y-m-d', strtotime($request->get('start_date'))):date('Y-m-d');
        $end_date = $request->get('end_date')?date('Y-m-d', strtotime($request->get('end_date'))):date('Y-m-d');

        $listData = AccountRealization::query()
            ->with(['currency'])
            ->where(function($q) use($start_date, $end_date){
                return $q->whereBetween('realization_date', [$start_date, $end_date]);
            })
            ->get();
        $lists = RealizationReportService::getReport($listData);

        return Excel::download(new RealizationMisReportExcel($lists,$start_date,$end_date), 'realizationMisReport.xlsx');



        // return view("basic-finance::accounting-realization.reports.mis-report", [
        //     'lists' => $lists,
        //     'start_date' => $start_date,
        //     'end_date' => $end_date
        // ]);
    }

    public function buyerStyleNames(Request $request)
    {
        $buyers = $request->get('buyerIds')?explode(',',$request->get('buyerIds')):[];
        return $styles = Order::query()
            ->whereIn('buyer_id', $buyers)->get(['style_name', 'job_no', 'id'])
            ->map(function ($order) {
                return [
                    'id' => $order['style_name'],
                    'text' => $order['style_name']
                ];
            })->unique()->values();
        return response()->json($styles);
    }
    public function fetchPo(Request $request)
    {
        $buyers = $request->get('buyerIds')?explode(',',$request->get('buyerIds')):[];
        
        $pos = Order::query()->with('purchaseOrders')->whereIn('buyer_id', $buyers)->get()->map(function ($item) {
            return collect($item['purchaseOrders'])->map(function ($po) {
                return [
                    'id' => $po['po_no'],
                    'text' => $po['po_no'],
                ];
            });
        })->unique()->collapse();

        return response()->json($pos);
    }
}