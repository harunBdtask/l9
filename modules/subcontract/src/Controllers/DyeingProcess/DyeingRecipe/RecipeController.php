<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;
use SkylarkSoft\GoRMG\Subcontract\Actions\DyeingProcessActions\DyeingRecipeActions\SyncRecipeDetails;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;
use SkylarkSoft\GoRMG\Subcontract\Requests\DyeingProcessRequests\DyeingRecipeRequests\RecipeFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RecipeController extends Controller
{
    const MACHINE_NAME = 'machine.name';

    public function index(Request $request)
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

        $subDyeingRecipes = SubDyeingRecipe::query()
            ->with([
                'supplier',
                'subDyeingBatch.fabricColor',
                'recipeRequisitions',
            ])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        $colors = Color::query()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view('subcontract::textile_module.dyeing_process.dyeing_recipe.index', [
            'stores' => $stores,
            'subDyeingRecipes' => $subDyeingRecipes,
            'factories' => $factories,
            'parties' => $parties,
            'shifts' => $shifts,
            'colors' => $colors,
        ]);
    }

    public function create()
    {
        return view('subcontract::textile_module.dyeing_process.dyeing_recipe.form');
    }

    public function store(RecipeFormRequest $request, SubDyeingRecipe $dyeingRecipe): JsonResponse
    {
        try {
            $dyeingRecipe->fill($request->all())->save();

            return response()->json([
                'message' => 'Recipe store successfully',
                'data' => $dyeingRecipe,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(SubDyeingRecipe $dyeingRecipe): array
    {
        $dyeingRecipe->load('subDyeingBatch');

        $dyeingBatch = $dyeingRecipe->getRelation('subDyeingBatch');

        // $machines = collect($dyeingBatch->machineAllocations)
        $machines = collect($dyeingRecipe->machineAllocations)
            ->pluck(self::MACHINE_NAME)
            ->implode(',');

        return [
            'recipe_uid' => $dyeingRecipe->getAttribute('recipe_uid'),
            'factory_id' => $dyeingRecipe->getAttribute('factory_id'),
            'supplier_id' => $dyeingRecipe->getAttribute('supplier_id'),
            'supplier' => $dyeingBatch->supplier->name,
            'batch_no' => $dyeingRecipe->subDyeingBatch->batch_no,
            'batch_id' => $dyeingRecipe->getAttribute('batch_id'),
            'order_nos' => collect($dyeingBatch->order_nos)->implode(', '),
            'machine_nos' => $machines,
            'fabric_description' => $dyeingBatch->material_description,
            'color' => $dyeingBatch->fabricColor->name,
            'gsm' => $dyeingBatch->gsm,
            'machine_capacity' => $dyeingBatch->total_machine_capacity,
            'yarn_description' => null,
            'ld_no' => $dyeingRecipe->ld_no,
            'yarn_lot' => $dyeingRecipe->yarn_lot,
            'fabric_weight' => $dyeingBatch->total_batch_weight,
            'liquor_ratio' => $dyeingRecipe->getAttribute('liquor_ratio'),
            'total_liq_level' => $dyeingRecipe->getAttribute('total_liq_level'),
            'shift_id' => $dyeingRecipe->getAttribute('shift_id'),
            'recipe_date' => $dyeingRecipe->getAttribute('recipe_date'),
            'remarks' => $dyeingRecipe->getAttribute('remarks'),
        ];
    }

    /**
     * @throws Throwable
     */
    public function update(
        RecipeFormRequest $request,
        SubDyeingRecipe   $dyeingRecipe,
        SyncRecipeDetails $syncRecipeDetails
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $dyeingRecipe->fill($request->all())->save();

            if ($dyeingRecipe->wasChanged('total_liq_level') &&
                $dyeingRecipe->recipeDetails()->count() > 0) {
                $syncRecipeDetails->syncTotalQty($dyeingRecipe);
            }
            DB::commit();

            return response()->json([
                'message' => 'Recipe update successfully',
                'data' => $dyeingRecipe,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy(SubDyeingRecipe $dyeingRecipe): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $dyeingRecipe->delete();
            DB::commit();

            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }

    public function view(Request $request, $id)
    {
        $dyeingRecipe = SubDyeingRecipe::with([
            'supplier',
            'subDyeingBatch.fabricType',
            'subDyeingBatch.color',
            'subDyeingBatch.batchDetails.fabricType',
            'subDyeingBatch.batchDetails.color',
            'recipeDetails.recipeOperation',
            'recipeDetails.recipeFunction',
            'recipeDetails.dsItem',
            'Shift',
            'recipeRequisitions',
        ])->where('id', $id)->first();
        $dyeingBatch = $dyeingRecipe->subDyeingBatch;

        $machines = collect($dyeingBatch->machineAllocations)
            ->pluck(self::MACHINE_NAME);

        return view('subcontract::textile_module.dyeing_process.dyeing_recipe.view', [
            'dyeingRecipe' => $dyeingRecipe,
            'machines' => $machines,
        ]);
    }

    public function pdf($id)
    {
        $dyeingRecipe = SubDyeingRecipe::with([
            'supplier',
            'subDyeingBatch.fabricType',
            'subDyeingBatch.color',
            'recipeDetails.recipeOperation',
            'recipeDetails.recipeFunction',
            'recipeDetails.dsItem',
            'Shift',
            'recipeRequisitions',
        ])->where('id', $id)->first();

        $dyeingBatch = $dyeingRecipe->subDyeingBatch;

        $machines = collect($dyeingBatch->machineAllocations)
            ->pluck(self::MACHINE_NAME);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('subcontract::textile_module.dyeing_process.dyeing_recipe.pdf.pdf', [
                'machines' => $machines,
                'dyeingRecipe' => $dyeingRecipe,
            ])->setPaper('a4')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('subcontract::pdf.footer'),
            ]);

        return $pdf->stream("{$id}_recipe.pdf");
    }
}
