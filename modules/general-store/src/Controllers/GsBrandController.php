<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsBrand;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\GeneralStore\Requests\GsBrandRequest;

class GsBrandController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $brands = GsBrand::filter($request->query('search'))->orderBy('created_at', 'DESC')->paginate();
        return view('general-store::pages.brands', [
            "brands" => $brands
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('general-store::forms.brand', ['brand' => null]);
    }

    /**
     * @param GsBrandRequest $request
     * @param Brand $brand
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(GsBrandRequest $request, GsBrand $brand)
    {
        try {
            $brand->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('/general-store/brands');
        } catch (\Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");
            return redirect()->back();
        }
    }

    /**
     * @param Brand $brand
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(GsBrand $brand)
    {
        return view('general-store::forms.brand', ['brand' => $brand]);
    }

    /**
     * @param GsBrand $brand
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(GsBrand $brand)
    {
        return view('general-store::forms.brand', ['brand' => $brand]);
    }

    /**
     * @param GsBrandRequest $request
     * @param GsBrand $brand
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(GsBrandRequest $request, GsBrand $brand)
    {
        try {
            $brand->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');
            return redirect('brands');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }

    /**
     * @param GsBrand $brand
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(GsBrand $brand)
    {
        try {
            $brand->delete();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');
            return redirect('brands');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }
}
