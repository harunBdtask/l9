<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess\DyeingRecipe;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipe;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipeRequisition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Throwable;

class SubDyeingRecipeRequisitionController extends Controller
{
    public function index(Request $request)
    {
        $dyeingRequisition = SubDyeingRecipeRequisition::query()
            ->with([
                'SubDyeingRecipe',
                'dsStore',
            ])
            ->orderBy('id', 'desc')
            ->search($request)
            ->paginate();

        $factories = Factory::query()->pluck('factory_name', 'id')
            ->prepend('Select', '');

        $dsStore = DsStoreModel::query()->pluck('name', 'id')
            ->prepend('Select', '');

        $subDyeingRecipe = SubDyeingRecipe::query()->pluck('recipe_uid', 'id')
            ->prepend('Select', '');

        return view('subcontract::textile_module.dyeing_process.dyeing_requisition.index', [
            'dyeingRequisition' => $dyeingRequisition,
            'factories' => $factories,
            'dsStore' => $dsStore,
            'subDyeingRecipe' => $subDyeingRecipe,
        ]);
    }

    /**
     * @param Request $request
     * @param SubDyeingRecipe $dyeingRecipe
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request, SubDyeingRecipe $dyeingRecipe): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $dyeingRecipe->recipeRequisitions()->create(array_merge($request->all(), [
                'factory_id' => $dyeingRecipe->factory_id,
            ]));

            Session::flash('success', 'Recipe requisition store successfully!');
            DB::commit();
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
