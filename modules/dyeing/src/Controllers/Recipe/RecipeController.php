<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Recipe;

use PDF;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Dyeing\Actions\SyncRecipeDetailsAction;
use SkylarkSoft\GoRMG\Dyeing\Requests\Recipe\DyeingRecipeFormRequest;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipe;
use SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\Formatters\DyeingRecipeFormatter;

class RecipeController extends Controller
{

    public function index(Request $request)
    {
        $recipes = DyeingRecipe::query()
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', '0');

        $buyers = Buyer::query()
            ->pluck('name', 'id')
            ->prepend('Select', '0');

        $shifts = Shift::query()
            ->pluck('shift_name', 'id')
            ->prepend('Select', '0');

        $stores = DsStoreModel::all()
            ->pluck('name', 'id');

        return view(PackageConst::VIEW_PATH . 'textile_modules.recipes.index', [
            'recipes' => $recipes,
            'factories' => $factories,
            'buyers' => $buyers,
            'shifts' => $shifts,
            'stores' => $stores,
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'textile_modules.recipes.form');
    }

    /**
     * @param DyeingRecipeFormRequest $request
     * @param DyeingRecipe $dyeingRecipe
     * @return JsonResponse
     */
    public function store(DyeingRecipeFormRequest $request, DyeingRecipe $dyeingRecipe): JsonResponse
    {
        try {
            $dyeingRecipe->fill($request->all())->save();

            return response()->json([
                'message' => 'Dyeing recipe data stored successfully',
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
     * @param DyeingRecipe $dyeingRecipe
     * @param DyeingRecipeFormatter $formatter
     * @return JsonResponse
     */
    public function edit(DyeingRecipe $dyeingRecipe, DyeingRecipeFormatter $formatter): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch dyeing recipe successfully',
                'data' => $formatter->format($dyeingRecipe),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param DyeingRecipeFormRequest $request
     * @param DyeingRecipe $dyeingRecipe
     * @param SyncRecipeDetailsAction $action
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(DyeingRecipeFormRequest $request,
                           DyeingRecipe            $dyeingRecipe,
                           SyncRecipeDetailsAction $action): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyeingRecipe->fill($request->all())->save();
            $action->syncTotalQty($dyeingRecipe);
            DB::commit();

            return response()->json([
                'message' => 'Dyeing recipe data updated successfully',
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
     * @param DyeingRecipe $dyeingRecipe
     * @return RedirectResponse
     */
    public function destroy(DyeingRecipe $dyeingRecipe): RedirectResponse
    {
        try {
            $dyeingRecipe->delete();

            Session::flash('success', 'Dyeing recipe deleted successfully');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }

    public function view($id)
    {
        $recipe = DyeingRecipe::query()
                    ->with([
                        'recipeDetails.recipeOperation',
                        'recipeDetails.recipeFunction',
                        'recipeDetails.unitOfMeasurement',
                        'recipeDetails.item',
                        'Shift',
                        'buyer',
                        'subDyeingBatch.color'
                    ])
                    ->where('id',$id)
                    ->first();
        return view(PackageConst::VIEW_PATH . 'textile_modules.recipes.view',[
            'recipe' => $recipe
        ]);
    }

    public function pdf($id)
    {
        $recipe = DyeingRecipe::query()
                    ->with([
                        'recipeDetails.recipeOperation',
                        'recipeDetails.recipeFunction',
                        'recipeDetails.unitOfMeasurement',
                        'recipeDetails.item',
                        'Shift',
                        'buyer',
                        'subDyeingBatch.color'
                    ])
                    ->where('id',$id)
                    ->first();

        $pdf = PDF::setOption('enable-local-file-access', true)
        ->loadView('dyeing::textile_modules.recipes.pdf', [
            'recipe' => $recipe,
        ])->setPaper('a4')->setOptions([
            'header-html' => view('skeleton::pdf.header'),
            'footer-html' => view('skeleton::pdf.footer'),
        ]);
        
        return $pdf->stream("{$id}_Dyeing_recipe.pdf");
    }

}
