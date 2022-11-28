<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\McInventory\Models\MachineBrand;
use SkylarkSoft\GoRMG\McInventory\Requests\MachineBrandFormRequest;


class MachineBrandController extends  Controller
{
    protected function machineBrands(Request $request): LengthAwarePaginator
    {
        return MachineBrand::query()->orderBy('id','desc')
            ->when($request->get('machine_name_filter'), function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->get('machine_name_filter')}%");
            })
            ->orderByDesc('id')
            ->paginate();
    }

    public function index(Request $request)
    {
        $machineBrands = $this->machineBrands($request);
        return view('McInventory::libraries.machine-brand',[
            'machineBrands'=>$machineBrands,
            'machineBrand'=>null
        ]);
    }

    public function  store(MachineBrandFormRequest $request,MachineBrand $machineBrand): RedirectResponse
    {
        try {
            $machineBrand->fill($request->all())->save();
            Session::flash('alert-success','Data Stored Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('machine-brand.index');
        }
    }

    public function edit(Request $request, MachineBrand $machineBrand)
    {
        try {
            $machineBrands = $this->machineBrands($request);
            return view('McInventory::libraries.machine-brand',[
                'machineBrands'=>$machineBrands,
                'machineBrand'=>$machineBrand
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
            return back();
        }
    }

    public function update(MachineBrandFormRequest $request, MachineBrand $machineBrand): RedirectResponse
    {
        try {
            $machineBrand->fill($request->all())->save();
            Session::flash('alert-success','Data Updated Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('machine-brand.index');
        }
    }

    public function destroy(MachineBrand $machineBrand): RedirectResponse
    {
        try {
            $machineBrand->delete();
            Session::flash('alert-danger','Data Deleted Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
