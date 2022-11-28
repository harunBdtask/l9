<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsRack;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\GeneralStore\Requests\GsRackRequest;


class GsRackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index(Request $request)
    {
        $racks = GsRack::filter($request->query('search'))->orderBy('created_at', 'DESC')->paginate();
        return view('general-store::pages.racks', [
            "racks" => $racks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        return view('general-store::forms.rack', ['rack' => null]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GsRackRequest $request
     * @param Rack $rack
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(GsRackRequest $request, GsRack $rack)
    {
        try {
            $rack->fill($request->all())->save();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('/general-store/racks');
        } catch (Exception $e) {
            Session::flash('alert-danger', "Something went wrong!{$e->getMessage()}");
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Rack $rack
     * @return Response
     */
    public function edit(GsRack $rack)
    {
        return view('general-store::forms.rack', ['rack' => $rack]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GsRackRequest $request
     * @param Rack $rack
     */
    public function update(GsRackRequest $request, GsRack $rack)
    {
        try {
            $rack->fill($request->all())->save();
            Session::flash('alert-success', 'Data Updated Successfully!!');
            return redirect('/general-store/racks');
        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rack $rack
     */
    public function destroy(GsRack $rack)
    {
        try {
            $rack->delete();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');
            return redirect('/general-store/racks');
        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');
            return redirect()->back();
        }
    }
}
