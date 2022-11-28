<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Exports\OrderReportExport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class OrderReportController extends Controller
{
    public function view(Request $request)
    {
        $factories = Factory::query()->pluck('factory_name', 'id')
                              ->prepend('Select', '');

        return view(PackageConst::VIEW_PATH.'report.order-report.order-report', [
            'factories' => $factories,
        ]);
    }

    public function factoryOrder(Request $request)
    {
        $factoryId = $request->factory;
        $orders = SubTextileOrder::query()->where('factory_id', $factoryId)->get();

        return response()->json($orders);
    }

    public function getReport(Request $request)
    {
        $order_id = $request->order_id;
        $order = SubTextileOrder::query()
                  ->with(['supplier',
                          'subDyeingProductionDetail.subDyeingProduction',
                          'subDryerDetail.subDryer',
                          'subSlittingDetail.subSlitting',
                          'subDyeingStenteringDetail.subDyeingStentering',
                          'subCompactorDetail.subCompactor',
                          'subDyeingTumbleDetail.tumble',
                          'subDyeingPeachDetail.peach',
                          'subDyeingFinishingProduction.finishingProduction',
                          'subDyeingDeliveryDetails.subDyeingGoodsDelivery',
                  ])
                  ->where('id', $order_id)
                  ->first();
        $batch = SubDyeingBatch::query()
        ->whereJsonContains('sub_textile_order_ids', ['sub_textile_order_id' => $order->id])
        ->pluck('id');
        $recipes = SubDyeingRecipe::query()
                   ->with('recipeDetails')
                   ->whereIn('batch_id', $batch)
                   ->get();
        //dd($order);

        return view(PackageConst::VIEW_PATH.'report.order-report.order-report-table', [
            'order' => $order,
            'recipes' => $recipes,
        ]);
    }

    public function pdf(Request $request)
    {
        $order_id = $request->order_id;
        $order = SubTextileOrder::query()
                  ->with(['supplier',
                          'subDyeingProductionDetail.subDyeingProduction',
                          'subDryerDetail.subDryer',
                          'subSlittingDetail.subSlitting',
                          'subDyeingStenteringDetail.subDyeingStentering',
                          'subCompactorDetail.subCompactor',
                          'subDyeingTumbleDetail.tumble',
                          'subDyeingPeachDetail.peach',
                          'subDyeingFinishingProduction.finishingProduction',
                          'subDyeingDeliveryDetails.subDyeingGoodsDelivery',
                  ])
                  ->where('id', $order_id)
                  ->first();
        $batch = SubDyeingBatch::query()
        ->whereJsonContains('sub_textile_order_ids', ['sub_textile_order_id' => $order->id])
        ->pluck('id');
        $recipes = SubDyeingRecipe::query()
                   ->with('recipeDetails')
                   ->whereIn('batch_id', $batch)
                   ->get();
        $pdf = PDF::setOption('enable-local-file-access', true)
        ->loadView('subcontract::report.pdf.order-report-pdf', [
            'order' => $order,
            'recipes' => $recipes,
        ])->setPaper('a4')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream('order_report.pdf');
    }

    public function excel(Request $request)
    {
        $order_id = $request->order_id;
        $order = SubTextileOrder::query()
                  ->with(['supplier',
                          'subDyeingProductionDetail.subDyeingProduction',
                          'subDryerDetail.subDryer',
                          'subSlittingDetail.subSlitting',
                          'subDyeingStenteringDetail.subDyeingStentering',
                          'subCompactorDetail.subCompactor',
                          'subDyeingTumbleDetail.tumble',
                          'subDyeingPeachDetail.peach',
                          'subDyeingFinishingProduction.finishingProduction',
                          'subDyeingDeliveryDetails.subDyeingGoodsDelivery',
                  ])
                  ->where('id', $order_id)
                  ->first();
        $batch = SubDyeingBatch::query()
        ->whereJsonContains('sub_textile_order_ids', ['sub_textile_order_id' => $order->id])
        ->pluck('id');
        $recipes = SubDyeingRecipe::query()
                   ->with('recipeDetails')
                   ->whereIn('batch_id', $batch)
                   ->get();

        return Excel::download(new OrderReportExport($order, $recipes), 'order_report.xlsx');
    }
}
