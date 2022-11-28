<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Reports;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWiseSewingReport;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class FloorWiseStyleInOutSummaryController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->data($request);
        $factory_id = $request->get('factory_id');
        $buyer_id = $request->get('buyer_id');
        $order_id = $request->get('order_id');

        $factories = Factory::query()->pluck('factory_name', 'id');
        $buyers = $buyer_id ? Buyer::query()->where('id', $buyer_id)->pluck('name', 'id') : [];
        $orders = $order_id ? Order::query()->where('id', $order_id)->pluck('style_name', 'id') : [];
        return view('manual-production::reports.floorWiseStyleInOutSummery.index', compact('factories', 'buyers', 'orders', 'factory_id', 'buyer_id', 'order_id', 'data'));
    }

    public function pdf(Request $request)
    {
        $data = $this->data($request);
        $pdf = PDF::loadView('manual-production::reports.floorWiseStyleInOutSummery.pdf', compact('data'));
//        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream("floor_wise_style_in_out_report.pdf");
    }

    public function excel()
    {

    }

    public function data($request)
    {
        $factoryId = $request->get('factory_id');
        $buyerId = $request->get('buyer_id');
        $orderId = $request->get('order_id');

        return ManualDateWiseSewingReport::query()
            ->with('floor', 'factory', 'buyer', 'order')
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->where('order_id', $orderId)
            ->get();
    }
}
