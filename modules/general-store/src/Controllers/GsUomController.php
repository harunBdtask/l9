<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsUom;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\GeneralStore\Requests\GsUomRequest;

class GsUomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $uoms = GsUom::filter($request->query('search'))->orderBy('created_at', 'DESC')->paginate();
        return view('general-store::pages.uoms', [
            "uoms" => $uoms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('general-store::forms.uom', ['uom' => null]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GsUomRequest $request
     * @param GsUom $uom
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(GsUomRequest $request, GsUom $uom)
    {
        try {
            $uom->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('/general-store/uom');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Uom $uom
     * @return \Illuminate\Http\Response
     */
    public function edit(GsUom $uom)
    {
        return view('general-store::forms.uom', ['uom' => $uom]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GsUomRequest $request
     * @param GsUom $uom
     * @return \Illuminate\Http\Response
     */
    public function update(GsUomRequest $request, GsUom $uom)
    {
        try {
            $uom->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');
            return redirect('/general-store/uom');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GsUom $uom
     * @return \Illuminate\Http\Response
     */
    public function destroy(GsUom $uom)
    {
        try {
            $uom->delete();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
            return redirect('/general-store/uom');
        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }
}
