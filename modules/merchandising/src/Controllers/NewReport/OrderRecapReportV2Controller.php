<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\NewReport;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Excel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Exports\OrderRecapNewReport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\NewReport\OrderRecapReportService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class OrderRecapReportV2Controller extends Controller
{

    public function index(Request $request)
    {
        $type = $request->get('type') ?? null;
        $factoryId = $request->input('factory_id') ?? null;
        $buyerId = $request->input('buyer_id') ?? null;
        $seasonId = $request->input('season_id') ?? null;
        $styleId = $request->input('style_id') ?? null;
        $poId = $request->input('po_id') ?? null;
        $fromDate = date($request->input('from_date')) ?? null;
        $toDate = date($request->input('to_date')) ?? null;
        $dateRangeType = $request->input('date_range_type') ?? null;

        $orderData = null;
        $summaryData = null;
        $factories = Factory::all('id', 'factory_name');
        $factoryId = $factoryId ?? $factories->first()['id'];
        $ids = [$factoryId, $buyerId, $seasonId, $styleId, $poId];
        $dates = [$fromDate, $toDate, $dateRangeType];
        if ($request->isMethod('post') && $factoryId) {
            $serviceData = OrderRecapReportService::getReport($ids, $dates);
            $orderData = $serviceData['table_report'];
            $summaryData = $serviceData['summary'];
        }

        $buyers = $factoryId ? Buyer::query()->where('factory_id', $factoryId)->get() : [];
        $seasons = $buyerId ? Season::query()->where("factory_id", $factoryId)
            ->where("buyer_id", $buyerId)->get() : [];
        $styles = $seasonId ? Order::query()->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)->where('season_id', $seasonId)->get(['id', 'style_name']) : [];
        $pos = $styleId ? PurchaseOrder::query()->where('order_id', $styleId)->get(['id', 'po_no']) : [];

        return view("merchandising::new-report.order-recap.view", compact([
            'factories',
            'factoryId',
            'buyerId',
            'buyers',
            'seasons',
            'styles',
            'seasonId',
            'styleId',
            'orderData',
            'summaryData',
            'pos',
            'poId',
            'fromDate',
            'toDate',
            'dateRangeType',
            'type'
        ]));
    }

    public function getSeasonsStyle(Request $request): JsonResponse
    {
        $factoryId = $request->get('factoryId') ?? null;
        $buyerId = $request->get('buyerId') ?? null;
        $seasonId = $request->get('seasonId') ?? null;
        $styles = Order::query()->where('factory_id', $factoryId)->where('buyer_id', $buyerId)
            ->where('season_id', $seasonId)->get(['id', 'style_name']);
        return response()->json($styles);
    }

    public function getPoId(Request $request): JsonResponse
    {
        $styleId = $request->get('styleId') ?? null;
        $pos = PurchaseOrder::query()->where('order_id', $styleId)->get(['id', 'po_no']);
        return response()->json($pos);
    }

    public function getReportPdf(Request $request): Response
    {
        $type = $request->get('type') ?? null;
        $factoryId = $request->input('factory_id') ?? null;
        $buyerId = $request->input('buyer_id') ?? null;
        $seasonId = $request->input('season_id') ?? null;
        $fromDate = date($request->input('from_date')) ?? null;
        $toDate = date($request->input('to_date')) ?? null;
        $styleId = $request->input('style_id') ?? null;
        $poId = $request->input('po_id') ?? null;
        $dateRangeType = $request->input('date_range_type') ?? null;
        $ids = [$factoryId, $buyerId, $seasonId, $styleId, $poId];
        $dates = [$fromDate, $toDate, $dateRangeType];
        $serviceData = OrderRecapReportService::getReport($ids, $dates);
        $orderData = $serviceData['table_report'];
        $summaryData = $serviceData['summary'];

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('merchandising::new-report.order-recap.pdf', [
                'orderData' => $orderData,
                'summaryData' => $summaryData,
                'type' => $type
            ])
            ->setPaper('a3', 'landscape');
        return $pdf->stream('new-order-recap-report.pdf');
    }

    public function getReportExcel(Request $request): BinaryFileResponse
    {
        $type = $request->get('type') ?? null;
        $factoryId = $request->input('factory_id') ?? null;
        $buyerId = $request->input('buyer_id') ?? null;
        $seasonId = $request->input('season_id') ?? null;
        $fromDate = date($request->input('from_date')) ?? null;
        $toDate = date($request->input('to_date')) ?? null;
        $styleId = $request->input('style_id') ?? null;
        $poId = $request->input('po_id') ?? null;
        $dateRangeType = $request->input('date_range_type') ?? null;
        $ids = [$factoryId, $buyerId, $seasonId, $styleId, $poId];
        $dates = [$fromDate, $toDate, $dateRangeType];
        $serviceData = OrderRecapReportService::getReport($ids, $dates);
        $orderData = $serviceData['table_report'];
        $summaryData = $serviceData['summary'];
        return Excel::download(new OrderRecapNewReport($orderData, $summaryData, $type), 'new_order_recap_report.xlsx');
    }
}
