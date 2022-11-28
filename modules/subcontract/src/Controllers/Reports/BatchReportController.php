<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Reports;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use PDF;
use SkylarkSoft\GoRMG\Subcontract\Exports\BatchReportExport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class BatchReportController extends Controller
{
    public function view(Request $request)
    {
        $factories = Factory::query()->pluck('factory_name', 'id')
        ->prepend('Select', '');

        return view(PackageConst::VIEW_PATH.'report.batch-report.batch-report', [
            'factories' => $factories,
        ]);
    }

    public function factoryBatch(Request $request)
    {
        $factory_id = $request->factory;
        //dd($factory_id);
        $batches = SubDyeingBatch::query()->where('factory_id', $factory_id)->get();

        return response()->json($batches);
    }

    public function getReport(Request $request)
    {
        $batch_id = $request->batch_id;
        $batch = SubDyeingBatch::query()
                 ->with([
                    'supplier',
                    'batchDetails.color',
                    'subDyeingRecipe.recipeDetails',
                    'subDyeingProductionDetail.subDyeingProduction',
                    'SubDryerDetail.subDryer',
                    'subSlittingDetail.subSlitting',
                    'subDyeingStenteringDetail.subDyeingStentering',
                    'subCompactorDetail.subCompactor',
                    'SubDyeingTumbleDetails.tumble',
                    'SubDyeingPeachDetail.peach',
                    'subDyeingFinishProductionDetail.finishingProduction',
                    'subDyeingGoodsDeliveryDetail.subDyeingGoodsDelivery',
                 ])
                 ->where('id', $batch_id)
                 ->first();
        $batchDetail = $batch->batchDetails->first();

        //dd($batch);
        return view(PackageConst::VIEW_PATH.'report.batch-report.batch-report-table', [
            'batch' => $batch,
            'batchDetail' => $batchDetail,
        ]);
    }

    public function pdf(Request $request)
    {
        $batch_id = $request->batch_id;
        $batch = SubDyeingBatch::query()
                 ->with([
                    'supplier',
                    'batchDetails.color',
                    'subDyeingRecipe.recipeDetails',
                    'subDyeingProductionDetail.subDyeingProduction',
                    'SubDryerDetail.subDryer',
                    'subSlittingDetail.subSlitting',
                    'subDyeingStenteringDetail.subDyeingStentering',
                    'subCompactorDetail.subCompactor',
                    'SubDyeingTumbleDetails.tumble',
                    'SubDyeingPeachDetail.peach',
                    'subDyeingFinishProductionDetail.finishingProduction',
                    'subDyeingGoodsDeliveryDetail.subDyeingGoodsDelivery',
                 ])
                 ->where('id', $batch_id)
                 ->first();
        $batchDetail = $batch->batchDetails->first();

        $pdf = PDF::setOption('enable-local-file-access', true)
        ->loadView('subcontract::report.pdf.batch-report-pdf', [
            'batch' => $batch,
            'batchDetail' => $batchDetail,
        ])->setPaper('a4')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);

        return $pdf->stream('batch_report.pdf');
    }

    public function excel(Request $request)
    {
        $batch_id = $request->batch_id;
        $batch = SubDyeingBatch::query()
                 ->with([
                    'supplier',
                    'batchDetails.color',
                    'subDyeingRecipe.recipeDetails',
                    'subDyeingProductionDetail.subDyeingProduction',
                    'SubDryerDetail.subDryer',
                    'subSlittingDetail.subSlitting',
                    'subDyeingStenteringDetail.subDyeingStentering',
                    'subCompactorDetail.subCompactor',
                    'SubDyeingTumbleDetails.tumble',
                    'SubDyeingPeachDetail.peach',
                    'subDyeingFinishProductionDetail.finishingProduction',
                    'subDyeingGoodsDeliveryDetail.subDyeingGoodsDelivery',
                 ])
                 ->where('id', $batch_id)
                 ->first();
        $batchDetail = $batch->batchDetails->first();

        return Excel::download(new BatchReportExport($batch, $batchDetail), 'batch_report.xlsx');
    }
}
