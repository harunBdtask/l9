<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\Subcontract\Requests\Libraries\SubDyeingUnitFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubDyeingUnitController extends Controller
{
    protected function subDyeingUnits(Request $request): LengthAwarePaginator
    {
        return SubDyeingUnit::query()
            ->with('factory')
            ->when($request->get('factory_filter'), function ($query) use ($request) {
                $query->where('factory_id', "{$request->get('factory_filter')}");
            })
            ->when($request->get('name_filter'), function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->get('name_filter')}%");
            })
            ->when($request->get('email_filter'), function ($query) use ($request) {
                $query->where('email', 'LIKE', "%{$request->get('email_filter')}%");
            })
            ->when($request->get('attention_filter'), function ($query) use ($request) {
                $query->where('attention', 'LIKE', "%{$request->get('attention_filter')}%");
            })
            ->when($request->get('address_filter'), function ($query) use ($request) {
                $query->where('address', 'LIKE', "%{$request->get('address_filter')}%");
            })->paginate();
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $subDyeingUnits = $this->subDyeingUnits($request);
        $factories = Factory::query()->pluck('factory_name', 'id')
            ->prepend('Select', '');

        return view(PackageConst::VIEW_PATH . 'libraries.sub_dyeing_unit', [
            'subDyeingUnits' => $subDyeingUnits,
            'dyeingUnit' => null,
            'factories' => $factories,
        ]);
    }

    /**
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        return view(PackageConst::VIEW_PATH . 'libraries.sub_dyeing_unit');
    }

    /**
     * @param SubDyeingUnitFormRequest $request
     * @param SubDyeingUnit $subDyeingUnit
     * @return RedirectResponse
     */
    public function store(SubDyeingUnitFormRequest $request, SubDyeingUnit $subDyeingUnit): RedirectResponse
    {
        try {
            $subDyeingUnit->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('sub-dyeing-unit.index');
        }
    }

    public function update(SubDyeingUnitFormRequest $request, SubDyeingUnit $subDyeingUnit): RedirectResponse
    {
        try {
            $subDyeingUnit->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('sub-dyeing-unit.index');
        }
    }

    public function edit(SubDyeingUnit $subDyeingUnit, Request $request)
    {
        try {
            $subDyeingUnits = $this->subDyeingUnits($request);
            $factories = Factory::query()->pluck('factory_name', 'id')
                ->prepend('Select', '');

            return view(PackageConst::VIEW_PATH . 'libraries.sub_dyeing_unit', [
                'subDyeingUnits' => $subDyeingUnits,
                'dyeingUnit' => $subDyeingUnit,
                'factories' => $factories,
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());

            return back();
        }
    }

    /**
     * @param SubDyeingUnit $subDyeingUnit
     * @return RedirectResponse
     */
    public function destroy(SubDyeingUnit $subDyeingUnit): RedirectResponse
    {
        try {
            $subDyeingUnit->delete();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
