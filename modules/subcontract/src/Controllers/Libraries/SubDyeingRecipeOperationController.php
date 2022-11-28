<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingRecipeOperation;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\Subcontract\Requests\Libraries\SubDyeingRecipeOperationRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubDyeingRecipeOperationController extends Controller
{
    protected function subDyeingRecipeOperation(Request $request): LengthAwarePaginator
    {
        return SubDyeingRecipeOperation::query()
            ->when($request->get('factory_filter'), function ($query) use ($request) {
                $query->where('factory_id', "{$request->get('factory_filter')}");
            })
            ->when($request->get('name_filter'), function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->get('name_filter')}%");
            })->paginate();
    }

    public function index(Request $request)
    {
        $subDyeingRecipeOperations = $this->subDyeingRecipeOperation($request);
        $factories = Factory::query()->pluck('factory_name', 'id')
            ->prepend('Select', '');

        return view(PackageConst::VIEW_PATH.'libraries.sub_dyeing_recipe_operation', [
            'subDyeingRecipeOperation' => null,
            'subDyeingRecipeOperations' => $subDyeingRecipeOperations,
            'factories' => $factories,
        ]);
    }

    public function store(SubDyeingRecipeOperationRequest $request, SubDyeingRecipeOperation $subDyeingRecipeOperation): RedirectResponse
    {
        try {
            $subDyeingRecipeOperation->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('sub-dyeing-recipe-operation.index');
        }
    }

    public function edit(SubDyeingRecipeOperation $subDyeingRecipeOperation, Request $request)
    {
        try {
            $subDyeingRecipeOperations = $this->subDyeingRecipeOperation($request);
            $factories = Factory::query()->pluck('factory_name', 'id')
                ->prepend('Select', '');

            return view(PackageConst::VIEW_PATH.'libraries.sub_dyeing_recipe_operation', [
                'subDyeingRecipeOperations' => $subDyeingRecipeOperations,
                'subDyeingRecipeOperation' => $subDyeingRecipeOperation,
                'factories' => $factories,
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());

            return back();
        }
    }

    public function update(
        SubDyeingRecipeOperationRequest $request,
        SubDyeingRecipeOperation $subDyeingRecipeOperation
    ): RedirectResponse {
        try {
            $subDyeingRecipeOperation->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('sub-dyeing-recipe-operation.index');
        }
    }

    public function destroy(SubDyeingRecipeOperation $subDyeingRecipeOperation)
    {
        try {
            $subDyeingRecipeOperation->delete();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
