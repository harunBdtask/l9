<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe;

use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;
use SkylarkSoft\GoRMG\Subcontract\Exports\MultipleRecipeDownloadExport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipeDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;

class MultipleRecipeDownloadController extends Controller
{
    public function index()
    {
        $stores = DsStoreModel::all()->pluck('name', 'id');

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $parties = Buyer::query()
            ->where('party_type', 'Subcontract')
            ->factoryFilter()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        $shifts = Shift::query()
            ->pluck('shift_name', 'id')
            ->prepend('Select', 0);

        $batchNo = SubDyeingRecipe::query()
            ->pluck('batch_no', 'batch_id')
            ->prepend('Select', 0);

        return view('subcontract::textile_module.dyeing_process.multiple_recipe_download.index', [
            'stores' => $stores,
            'factories' => $factories,
            'parties' => $parties,
            'shifts' => $shifts,
            'batchNo' => $batchNo,
            'subDyeingRecipes' => [],
        ]);
    }

    public function search(Request $request)
    {
        $stores = DsStoreModel::all()->pluck('name', 'id');

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', 0);

        $parties = Buyer::query()
            ->where('party_type', 'Subcontract')
            ->factoryFilter()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        $shifts = Shift::query()
            ->pluck('shift_name', 'id')
            ->prepend('Select', 0);

        $batchNo = SubDyeingRecipe::query()
            ->pluck('batch_no', 'batch_id')
            ->prepend('Select', 0);

        $recipeUid = $request->input('recipe_uid');
        $recipeDate = $request->input('recipe_date');
        $factoryId = $request->input('factory_id');
        $supplierId = $request->input('supplier_id');
        $batchId = $request->input('batch_id');
        $liquorRatio = $request->input('liquor_ratio');
        $totalLiqLevel = $request->input('total_liq_level');
        $shiftId = $request->input('shift_id');
        $totalBatchWeight = $request->input('total_batch_weight');

        $subDyeingRecipe = SubDyeingRecipe::query()
            ->with('subDyeingBatch')
            ->when($recipeUid, function (Builder $builder) use ($recipeUid) {
                $builder->where('recipe_uid', $recipeUid);
            })->when($recipeDate, function (Builder $builder) use ($recipeDate) {
                $builder->where('recipe_date', $recipeDate);
            })->when($factoryId, function (Builder $builder) use ($factoryId) {
                $builder->where('factory_id', $factoryId);
            })->when($supplierId, function (Builder $builder) use ($supplierId) {
                $builder->where('supplier_id', $supplierId);
            })->when($batchId, function (Builder $builder) use ($batchId) {
                $builder->whereIn('batch_id', $batchId);
            })->when($liquorRatio, function (Builder $builder) use ($liquorRatio) {
                $builder->where('liquor_ratio', $liquorRatio);
            })->when($totalLiqLevel, function (Builder $builder) use ($totalLiqLevel) {
                $builder->where('total_liq_level', $totalLiqLevel);
            })->when($shiftId, function (Builder $builder) use ($shiftId) {
                $builder->where('shift_id', $shiftId);
            })->when($totalBatchWeight, function (Builder $builder) use ($totalBatchWeight) {
                $builder->whereHas('subDyeingBatch', function (Builder $query) use ($totalBatchWeight) {
                    $query->where('total_batch_weight', $totalBatchWeight);
                });
            })
            ->get();

        return view('subcontract::textile_module.dyeing_process.multiple_recipe_download.index', [
            'subDyeingRecipes' => $subDyeingRecipe,
            'stores' => $stores,
            'factories' => $factories,
            'parties' => $parties,
            'shifts' => $shifts,
            'batchNo' => $batchNo,
        ]);
    }

    public function excelDownload(Request $request)
    {
        $recipes = explode(',', $request->query('recipes'));

        $dyeingRecipe = SubDyeingRecipeDetail::query()
            ->with([
                'recipe.supplier',
                'recipe.subDyeingBatch.fabricType',
                'recipe.subDyeingBatch.color',
                'recipe.subDyeingBatch.batchDetails.fabricType',
                'recipe.subDyeingBatch.batchDetails.color',
                'recipeOperation',
                'recipeFunction',
                'dsItem',
                'unitOfMeasurement',
                'recipe.Shift',
                'recipe.recipeRequisitions',
            ])
            ->select('*', DB::raw("SUM(percentage) as total_percentage,SUM(g_per_ltr) as total_g_per_ltr,SUM(total_qty) as sum_total_qty"))
            ->whereIn('sub_dyeing_recipe_id', $recipes)
            ->groupBy('recipe_operation_id', 'item_id')
            ->get()
            ->groupBy('recipe_operation_id');

        return Excel::download(new MultipleRecipeDownloadExport($dyeingRecipe), 'multiple_recipe_download.xlsx');
    }

    public function pdfDownload(Request $request)
    {
        $recipes = explode(',', $request->query('recipes'));

        $dyeingRecipe = SubDyeingRecipeDetail::query()
            ->with([
                'recipe.supplier',
                'recipe.subDyeingBatch.fabricType',
                'recipe.subDyeingBatch.color',
                'recipe.subDyeingBatch.batchDetails.fabricType',
                'recipe.subDyeingBatch.batchDetails.color',
                'recipeOperation',
                'recipeFunction',
                'dsItem',
                'unitOfMeasurement',
                'recipe.Shift',
                'recipe.recipeRequisitions',
            ])
            ->select('*', DB::raw("SUM(percentage) as total_percentage,SUM(g_per_ltr) as total_g_per_ltr,SUM(total_qty) as sum_total_qty"))
            ->whereIn('sub_dyeing_recipe_id', $recipes)
            ->groupBy('recipe_operation_id', 'item_id')
            ->get()
            ->groupBy('recipe_operation_id');

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::textile_module.dyeing_process.multiple_recipe_download.pdf', [
                'dyeingRecipe' => $dyeingRecipe,
            ])->setPaper('a4')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('multiple_recipe_download.pdf');
    }
}
