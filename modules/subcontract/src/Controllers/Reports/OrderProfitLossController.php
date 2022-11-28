<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Exports\OrderProfitLossReportExport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProductionDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubCompactorDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDryerDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingFinishingProduction\SubDyeingFinishingProductionDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingPeach\SubDyeingPeachDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingStenteringDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingTumble\SubDyeingTumbleDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubSlittingDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class OrderProfitLossController extends Controller
{
    public function view()
    {
        $suppliers = Buyer::query()->pluck('name', 'id')->prepend('Select', '');

        return view(PackageConst::VIEW_PATH . 'report.order-profit-loss-analysis.order-profit-loss-analysis', [
            'suppliers' => $suppliers,
        ]);
    }

    public function getOrder(Request $request)
    {
        $supplier = $request->supplier_id;
        $order = SubTextileOrder::query()->where('supplier_id', $supplier)->get();

        return response()->json($order);
    }

    public function getReport(Request $request)
    {
        $supplierId = $request->supplier_id;
        $orderId = $request->order_id;


        $order = SubTextileOrder::query()
            ->with([
                'supplier',
                'subTextileOrderDetails',
            ])
            ->where('id', $orderId)->first();
        $orderDetails = $order->subTextileOrderDetails->first();

        $totalOverAllCost = 0;
        $totalSubDyeingCost = SubDyeingProductionDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubDryerCost = SubDryerDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubSlittingCost = SubSlittingDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalStenteringCost = SubDyeingStenteringDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubCompactorCost = SubCompactorDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubTumbleCost = SubDyeingTumbleDetail::query()->where('sub_textile_order_id', $orderId)->sum('total_cost');
        $totalSubDyeingPeachCost = SubDyeingPeachDetail::query()->where('sub_textile_order_id', $orderId)->sum('total_cost');
        $totalSubDyeingFinishingCost = SubDyeingFinishingProductionDetail::query()->where('sub_textile_order_id', $orderId)->sum('total_cost');

        $totalOverAllCost += $totalSubDyeingCost + $totalSubDryerCost + $totalSubSlittingCost + $totalStenteringCost
            + $totalSubCompactorCost + $totalSubTumbleCost + $totalSubDyeingPeachCost + $totalSubDyeingFinishingCost;

        return view(PackageConst::VIEW_PATH . 'report.order-profit-loss-analysis.order-profit-loss-analysis-table', [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'totalSubDyeingCost' => $totalSubDyeingCost,
            'totalSubDryerCost' => $totalSubDryerCost,
            'totalSubSlittingCost' => $totalSubSlittingCost,
            'totalStenteringCost' => $totalStenteringCost,
            'totalSubCompactorCost' => $totalSubCompactorCost,
            'totalSubTumbleCost' => $totalSubTumbleCost,
            'totalSubDyeingPeachCost' => $totalSubDyeingPeachCost,
            'totalSubDyeingFinishingCost' => $totalSubDyeingFinishingCost,
            'totalOverAllCost' => $totalOverAllCost,
        ]);
    }

    public function pdf(Request $request)
    {
        $supplierId = $request->supplier_id;
        $orderId = $request->order_id;


        $order = SubTextileOrder::query()
            ->with([
                'supplier',
                'subTextileOrderDetails',
            ])
            ->where('id', $orderId)->first();
        $orderDetails = $order->subTextileOrderDetails->first();

        $totalOverAllCost = 0;
        $totalSubDyeingCost = SubDyeingProductionDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubDryerCost = SubDryerDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubSlittingCost = SubSlittingDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalStenteringCost = SubDyeingStenteringDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubCompactorCost = SubCompactorDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubTumbleCost = SubDyeingTumbleDetail::query()->where('sub_textile_order_id', $orderId)->sum('total_cost');
        $totalSubDyeingPeachCost = SubDyeingPeachDetail::query()->where('sub_textile_order_id', $orderId)->sum('total_cost');
        $totalSubDyeingFinishingCost = SubDyeingFinishingProductionDetail::query()->where('sub_textile_order_id', $orderId)->sum('total_cost');

        $totalOverAllCost += $totalSubDyeingCost + $totalSubDryerCost + $totalSubSlittingCost + $totalStenteringCost
            + $totalSubCompactorCost + $totalSubTumbleCost + $totalSubDyeingPeachCost + $totalSubDyeingFinishingCost;

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::report.pdf.order-profit-loss-pdf', [
                'order' => $order,
                'orderDetails' => $orderDetails,
                'totalSubDyeingCost' => $totalSubDyeingCost,
                'totalSubDryerCost' => $totalSubDryerCost,
                'totalSubSlittingCost' => $totalSubSlittingCost,
                'totalStenteringCost' => $totalStenteringCost,
                'totalSubCompactorCost' => $totalSubCompactorCost,
                'totalSubTumbleCost' => $totalSubTumbleCost,
                'totalSubDyeingPeachCost' => $totalSubDyeingPeachCost,
                'totalSubDyeingFinishingCost' => $totalSubDyeingFinishingCost,
                'totalOverAllCost' => $totalOverAllCost,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('order_profit_loss.pdf');
    }

    public function excel(Request $request)
    {
        $supplierId = $request->supplier_id;
        $orderId = $request->order_id;


        $order = SubTextileOrder::query()
            ->with([
                'supplier',
                'subTextileOrderDetails',
            ])
            ->where('id', $orderId)->first();
        $orderDetails = $order->subTextileOrderDetails->first();

        $totalOverAllCost = 0;
        $totalSubDyeingCost = SubDyeingProductionDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubDryerCost = SubDryerDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubSlittingCost = SubSlittingDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalStenteringCost = SubDyeingStenteringDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubCompactorCost = SubCompactorDetail::query()->where('order_id', $orderId)->sum('total_cost');
        $totalSubTumbleCost = SubDyeingTumbleDetail::query()->where('sub_textile_order_id', $orderId)->sum('total_cost');
        $totalSubDyeingPeachCost = SubDyeingPeachDetail::query()->where('sub_textile_order_id', $orderId)->sum('total_cost');
        $totalSubDyeingFinishingCost = SubDyeingFinishingProductionDetail::query()->where('sub_textile_order_id', $orderId)->sum('total_cost');

        $totalOverAllCost += $totalSubDyeingCost + $totalSubDryerCost + $totalSubSlittingCost + $totalStenteringCost
            + $totalSubCompactorCost + $totalSubTumbleCost + $totalSubDyeingPeachCost + $totalSubDyeingFinishingCost;

        $reportData = [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'totalSubDyeingCost' => $totalSubDyeingCost,
            'totalSubDryerCost' => $totalSubDryerCost,
            'totalSubSlittingCost' => $totalSubSlittingCost,
            'totalStenteringCost' => $totalStenteringCost,
            'totalSubCompactorCost' => $totalSubCompactorCost,
            'totalSubTumbleCost' => $totalSubTumbleCost,
            'totalSubDyeingPeachCost' => $totalSubDyeingPeachCost,
            'totalSubDyeingFinishingCost' => $totalSubDyeingFinishingCost,
            'totalOverAllCost' => $totalOverAllCost,
        ];

        return Excel::download(new OrderProfitLossReportExport($reportData), 'order_profit_loss_excel.xlsx');
    }
}
