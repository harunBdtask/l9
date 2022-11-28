<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\Recipe;

use Exception;
use SkylarkSoft\GoRMG\Dyeing\PackageConst;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipe;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingRecipe\DyeingRecipeRequisition;

class RecipeRequisitionController extends Controller
{

    public function index(Request $request)
    {
        $requisitions = DyeingRecipeRequisition::query()
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        $factories = Factory::query()
            ->pluck('factory_name', 'id')
            ->prepend('Select', '0');

        $recipeNos = DyeingRecipe::query()
            ->pluck('unique_id', 'id')
            ->prepend('Select', 0);

        $stores = DsStoreModel::all()
            ->pluck('name', 'id')
            ->prepend('Select', 0);

        return view(PackageConst::VIEW_PATH . 'textile_modules.recipes.requisitions.index', [
            'requisitions' => $requisitions,
            'factories' => $factories,
            'recipeNos' => $recipeNos,
            'stores' => $stores,
        ]);
    }

    /**
     * @param Request $request
     * @param DyeingRecipe $dyeingRecipe
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request, DyeingRecipe $dyeingRecipe): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $dyeingRecipe->recipeRequisitions()->create(array_merge($request->all(), [
                'factory_id' => $dyeingRecipe->factory_id,
            ]));

            Session::flash('success', 'Dyeing recipe requisition store successfully!');
            DB::commit();
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }

}
