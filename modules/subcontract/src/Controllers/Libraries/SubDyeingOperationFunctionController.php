<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingOperationFunction;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingRecipeOperation;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\Subcontract\Requests\Libraries\SubDyeingOperationFunctionRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubDyeingOperationFunctionController extends Controller
{
    protected function subDyeingOperationFunction(Request $request): LengthAwarePaginator
    {
        return SubDyeingOperationFunction::query()
            ->with(['dyeingRecipeOperation'])
            ->when($request->get('factory_filter'), function ($query) use ($request) {
                $query->where('factory_id', "{$request->get('factory_filter')}");
            })
            ->when($request->get('function_name'), function ($query) use ($request) {
                $query->where('function_name', 'LIKE', "%{$request->get('function_name')}%");
            })
            ->when($request->get('name'), function ($query) use ($request) {
                $query->where('dyeing_recipe_operation_id', 'LIKE', "%{$request->get('name')}%");
            })->paginate();
    }

    public function index(Request $request)
    {
        $subDyeingOperationFunctions = $this->subDyeingOperationFunction($request);
        $factories = Factory::query()->pluck('factory_name', 'id')
            ->prepend('Select', '');
        $subDyeingRecipeOperation = SubDyeingRecipeOperation::query()
            ->pluck('name', 'id')
            ->prepend('Select', '');
        //dd($subDyeingRecipeOperation);

        return view(PackageConst::VIEW_PATH.'libraries.sub_dyeing_operation_function', [
            'subDyeingOperationFunction' => null,
            'subDyeingOperationFunctions' => $subDyeingOperationFunctions,
            'factories' => $factories,
            'dyeingRecipe' => null,
            'subDyeingRecipeOperation' => $subDyeingRecipeOperation,
        ]);
    }

    public function dyeingReOperation(Request $request)
    {
        $dyeingRecipe = SubDyeingRecipeOperation::where('factory_id', $request->factory)->get();

        return response()->json($dyeingRecipe);
    }

    public function store(
        SubDyeingOperationFunctionRequest $request,
        SubDyeingOperationFunction $dyeingOperationFunction
    ): RedirectResponse {
        try {
            $dyeingOperationFunction->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('sub-dyeing-operation-function.index');
        }
    }

    public function edit(SubDyeingOperationFunction $subDyeingOperationFunction, Request $request)
    {
        try {
            $subDyeingOperationFunctions = $this->subDyeingOperationFunction($request);
            $factories = Factory::query()->pluck('factory_name', 'id')
                ->prepend('Select', '');
            $dyeingRecipeFactoryId = SubDyeingRecipeOperation::query()
                ->where('id', $subDyeingOperationFunction->dyeing_recipe_operation_id)
                ->first();
            $dyeingRecipe = SubDyeingRecipeOperation::query()
                ->where('factory_id', $dyeingRecipeFactoryId->factory_id)
                ->get();

            $subDyeingRecipeOperation = SubDyeingRecipeOperation::query()
            ->pluck('name', 'id')
            ->prepend('Select', '');

            return view(PackageConst::VIEW_PATH.'libraries.sub_dyeing_operation_function', [
                'subDyeingOperationFunctions' => $subDyeingOperationFunctions,
                'subDyeingOperationFunction' => $subDyeingOperationFunction,
                'factories' => $factories,
                'dyeingRecipe' => $dyeingRecipe,
                'subDyeingRecipeOperation' => $subDyeingRecipeOperation,
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());

            return back();
        }
    }

    public function update(
        SubDyeingOperationFunctionRequest $request,
        SubDyeingOperationFunction $subDyeingOperationFunction
    ): RedirectResponse {
        try {
            $subDyeingOperationFunction->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('sub-dyeing-operation-function.index');
        }
    }

    public function destroy(SubDyeingOperationFunction $dyeingOperationFunction)
    {
        try {
            $dyeingOperationFunction->delete();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
