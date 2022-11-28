<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Exports\PartyAndOrderWiseReportExport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class PartyAndOrderWiseReportController extends Controller
{
    public function view()
    {
        $factories = Factory::query()->pluck('factory_name', 'id')->prepend('Select', '');

        return view(PackageConst::VIEW_PATH.'report.party-and-order-wise-report.party-and-order-wise-report', [
            'factories' => $factories,
        ]);
    }

    public function getSupplier(Request $request)
    {
        $factory = $request->factory;
        $supplier = Buyer::query()->where('factory_id', $factory)->get();

        return response()->json($supplier);
    }

    public function getOrder(Request $request)
    {
        $supplier = $request->supplier_id;
        $orders = SubTextileOrder::where('supplier_id', $supplier)->get();

        return response()->json($orders);
    }

    public function getReport(Request $request)
    {
        $factory = $request->factory_id;
        $supplier = $request->supplier_id;
        $order_id = $request->order_id;

        // dd($factory);

        $orderDetails = SubTextileOrder::query()
        ->with([
            'subTextileOrderDetails.color',
            'subTextileOrderDetails.subGreyStoreIssueDetail',
            'subTextileOrderDetails.subGreyStoreReceiveDetail',
            'subTextileOrderDetails.subDyeingBatchDetail',
            'subTextileOrderDetails.subDyeingFinishingProductionDetail',
            'subTextileOrderDetails.subDyeingProductionDetails',
            'subTextileOrderDetails.subDyeingGoodsDeliveryDetail',
            'supplier',
        ])
        ->when($factory, function (Builder $query) use ($factory) {
            $query->where('factory_id', $factory);
        })
        ->when($supplier, function (Builder $query) use ($supplier) {
            $query->where('supplier_id', $supplier);
        })
        ->when($order_id, function (Builder $query) use ($order_id) {
            $query->where('id', $order_id);
        })
        ->get();
        //dd($orderDetails);
        //return response()->json($orderDetails);
        return view(PackageConst::VIEW_PATH.'report.party-and-order-wise-report.party-and-order-wise-report-table', [
            'orderDetails' => $orderDetails,
        ]);
    }

    public function pdf(Request $request)
    {
        $factory = $request->factory_id;
        $supplier = $request->supplier_id;
        $order_id = $request->order_id;

        // dd($factory);

        $orderDetails = SubTextileOrder::query()
        ->with([
            'subTextileOrderDetails.color',
            'subTextileOrderDetails.subGreyStoreIssueDetail',
            'subTextileOrderDetails.subGreyStoreReceiveDetail',
            'subTextileOrderDetails.subDyeingBatchDetail',
            'subTextileOrderDetails.subDyeingFinishingProductionDetail',
            'subTextileOrderDetails.subDyeingProductionDetails',
            'subTextileOrderDetails.subDyeingGoodsDeliveryDetail',
            'supplier',
        ])
        ->when($factory, function (Builder $query) use ($factory) {
            $query->where('factory_id', $factory);
        })
        ->when($supplier, function (Builder $query) use ($supplier) {
            $query->where('supplier_id', $supplier);
        })
        ->when($order_id, function (Builder $query) use ($order_id) {
            $query->where('id', $order_id);
        })
        ->get();
        $pdf = PDF::setOption('enable-local-file-access', true)
        ->loadView('subcontract::report.pdf.party-and-order-wise-report-pdf', [
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
        $supplier = $request->supplier_id;
        $order_id = $request->order_id;

        // dd($factory);

        $orderDetails = SubTextileOrder::query()
        ->with([
            'subTextileOrderDetails.color',
            'subTextileOrderDetails.subGreyStoreIssueDetail',
            'subTextileOrderDetails.subGreyStoreReceiveDetail',
            'subTextileOrderDetails.subDyeingBatchDetail',
            'subTextileOrderDetails.subDyeingFinishingProductionDetail',
            'subTextileOrderDetails.subDyeingProductionDetails',
            'subTextileOrderDetails.subDyeingGoodsDeliveryDetail',
            'supplier',
        ])
        ->when($factory, function (Builder $query) use ($factory) {
            $query->where('factory_id', $factory);
        })
        ->when($supplier, function (Builder $query) use ($supplier) {
            $query->where('supplier_id', $supplier);
        })
        ->when($order_id, function (Builder $query) use ($order_id) {
            $query->where('id', $order_id);
        })
        ->get();

        return Excel::download(new PartyAndOrderWiseReportExport($orderDetails), 'party_and_order_wise_excel.xlsx');
    }
}
