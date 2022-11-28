<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\DyesStore\Models\DsBrand;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\DyesStore\Requests\DsBrandRequest;

class DsBrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = DsBrand::query()->filter($request->query('search'))->orderBy('created_at', 'DESC')->paginate();

        return view('dyes-store::pages.brands', [
            "brands" => $brands
        ]);
    }

    public function create()
    {
        return view('dyes-store::forms.brand', ['brand' => null]);
    }

    public function store(DsBrandRequest $request, DsBrand $brand)
    {
        try {
            $brand->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('/dyes-store/brands');
        } catch (\Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");

            return redirect()->back();
        }
    }

    public function show(DsBrand $brand)
    {
        return view('dyes-store::forms.brand', ['brand' => $brand]);
    }

    public function edit(DsBrand $brand)
    {
        return view('dyes-store::forms.brand', ['brand' => $brand]);
    }

    public function update(DsBrandRequest $request, DsBrand $brand)
    {
        try {
            $brand->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('/dyes-store/brands');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function destroy(DsBrand $brand)
    {
        try {
            $brand->delete();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('/dyes-store/brands');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
