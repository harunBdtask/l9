<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Reports\PartyAndOrderWiseController;

use PDF;
use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Dyeing\Exports\PartyAndOrderWiseReportExport;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;


class PartyAndOrderWiseReportController extends Controller
{
    public function view()
    {
        $factories = Factory::query()->pluck('factory_name', 'id')->prepend('Select','');
        return view(PackageConst::VIEW_PATH.'reports.party-and-order-wise-report.party-and-order-wise-report',[
            'factories' => $factories
        ]);
    }

    public function getBuyer(Request $request)
    {
        $factory = $request->factory;
        $buyer = Buyer::query()->where('factory_id',$factory)->get();
        return response()->json($buyer);
    }

    public function getOrder(Request $request)
    {
        $buyer = $request->buyer_id;
        $orders = TextileOrder::where('buyer_id',$buyer)->get();
        return response()->json($orders);
    }

    public function getReport(Request $request)
    {
        $factory = $request->factory_id;
        $buyer = $request->buyer_id;
        $order_id = $request->order_id;

        // dd($factory);

        $orderDetails = TextileOrder::query()
        ->with([
            'textileOrderDetails.color',
            'textileOrderDetails.dyeingBatchDetail',
            'textileOrderDetails.dyeingFinishingProductionDetail',
            'textileOrderDetails.dyeingProductionDetails',
            'textileOrderDetails.dyeingGoodsDeliveryDetail',
            'buyer'
        ])
        ->when($factory, function (Builder $query) use ($factory) {
            $query->where('factory_id',$factory);
        })
        ->when($buyer, function (Builder $query) use ($buyer) {
            $query->where('buyer_id',$buyer);
        })
        ->when($order_id, function (Builder $query) use ($order_id) {
            $query->where('id',$order_id);
        })
        ->get();
        //dd($orderDetails);
        //return response()->json($orderDetails);
        return view(PackageConst::VIEW_PATH.'reports.party-and-order-wise-report.party-and-order-wise-report-table',[
            'orderDetails' => $orderDetails
        ]);
    }

    public function pdf(Request $request)
    {
        $factory = $request->factory_id;
        $buyer = $request->buyer_id;
        $order_id = $request->order_id;

        // dd($factory);

        $orderDetails = TextileOrder::query()
        ->with([
            'textileOrderDetails.color',
            'textileOrderDetails.dyeingBatchDetail',
            'textileOrderDetails.dyeingFinishingProductionDetail',
            'textileOrderDetails.dyeingProductionDetails',
            'textileOrderDetails.dyeingGoodsDeliveryDetail',
            'buyer'
        ])
        ->when($factory, function (Builder $query) use ($factory) {
            $query->where('factory_id',$factory);
        })
        ->when($buyer, function (Builder $query) use ($buyer) {
            $query->where('buyer_id',$buyer);
        })
        ->when($order_id, function (Builder $query) use ($order_id) {
            $query->where('id',$order_id);
        })
        ->get();
        $pdf = PDF::setOption('enable-local-file-access', true)
        ->loadView('dyeing::reports.party-and-order-wise-report.party-and-order-wise-report-pdf', [
            'orderDetails' => $orderDetails,
        ])->setPaper('a4')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream('party_and_order_wise_report.pdf');
    }

    public function excel(Request $request)
    {
        $factory = $request->factory_id;
        $buyer = $request->buyer_id;
        $order_id = $request->order_id;

        // dd($factory);

        $orderDetails = TextileOrder::query()
        ->with([
            'textileOrderDetails.color',
            'textileOrderDetails.dyeingBatchDetail',
            'textileOrderDetails.dyeingFinishingProductionDetail',
            'textileOrderDetails.dyeingProductionDetails',
            'textileOrderDetails.dyeingGoodsDeliveryDetail',
            'buyer'
        ])
        ->when($factory, function (Builder $query) use ($factory) {
            $query->where('factory_id',$factory);
        })
        ->when($buyer, function (Builder $query) use ($buyer) {
            $query->where('buyer_id',$buyer);
        })
        ->when($order_id, function (Builder $query) use ($order_id) {
            $query->where('id',$order_id);
        })
        ->get();
        return Excel::download(new PartyAndOrderWiseReportExport($orderDetails), 'party_and_order_wise_excel.xlsx');
    }
}