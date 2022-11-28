<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubContractGreyStore;
use SkylarkSoft\GoRMG\Subcontract\PackageConst;
use SkylarkSoft\GoRMG\Subcontract\Requests\Libraries\SubContractGreyStoreFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubContractGreyStoreController extends controller
{
    protected function subGreyStores(Request $request): LengthAwarePaginator
    {
        return SubContractGreyStore::query()
            ->with('factory')
            ->when($request->get('factory_filter'), function ($query) use ($request) {
                $query->where('factory_id', "{$request->get('factory_filter')}");
            })
            ->when($request->get('name_filter'), function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->get('name_filter')}%");
            })->paginate();
    }

    public function index(Request $request)
    {
        $subGreyStores = $this->subGreyStores($request);
        $factories = Factory::query()->pluck('factory_name', 'id')
            ->prepend('Select', '');

        return view(PackageConst::VIEW_PATH.'libraries.sub_grey_store', [
            'greyStore' => null,
            'factories' => $factories,
            'subGreyStores' => $subGreyStores,
        ]);
    }

    public function create()
    {
        return view(PackageConst::VIEW_PATH.'libraries.sub_grey_store');
    }

    public function store(SubContractGreyStoreFormRequest $request, SubContractGreyStore $subGreyStore): RedirectResponse
    {
        try {
            $subGreyStore->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('sub-grey-store.index');
        }
    }

    public function update(SubContractGreyStoreFormRequest $request, SubContractGreyStore $subGreyStore): RedirectResponse
    {
        try {
            $subGreyStore->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('sub-grey-store.index');
        }
    }

    public function edit(SubContractGreyStore $subGreyStore, Request $request)
    {
        //dd($subGreyStore);
        try {
            $subGreyStores = $this->subGreyStores($request);
            $factories = Factory::query()->pluck('factory_name', 'id')
                ->prepend('Select', '');

            return view(PackageConst::VIEW_PATH.'libraries.sub_grey_store', [
                'greyStore' => $subGreyStore,
                'factories' => $factories,
                'subGreyStores' => $subGreyStores,
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());

            return back();
        }
    }

    /**
     * @return RedirectResponse
     */
    public function destroy(SubContractGreyStore $subGreyStore)
    {
        try {
            $subGreyStore->delete();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
