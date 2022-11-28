<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\DyesStore\Requests\DsStoreRequest;

class DsStoreController extends Controller
{
    public function index()
    {
        $stores = DsStoreModel::query()->orderBy('created_at', 'DESC')->paginate();

        return view('dyes-store::pages.stores', [
            "stores" => $stores
        ]);
    }

    public function create()
    {
        return view('dyes-store::forms.store', ['store' => null]);
    }

    public function store(DsStoreRequest $request, DsStoreModel $storeModel)
    {
        try {
            $storeModel->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('/dyes-store/stores');
        } catch (\Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");

            return redirect()->back();
        }
    }

    public function show(DsStoreModel $storeModel)
    {
        return view('dyes-store::forms.store', ['store' => $storeModel]);
    }

    public function edit($id)
    {
        $stores = DsStoreModel::query()->findOrFail($id);
        return view('dyes-store::forms.store', ['store' => $stores]);
    }

    public function update(DsStoreRequest $request, $id)
    {
        try {
            $stores = DsStoreModel::query()->where('id',$id)->first();
            $stores->update($request->all());
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('/dyes-store/stores');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function destroy(DsStoreModel $storeModel)
    {
        try {
            $storeModel->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('/dyes-store/stores');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
