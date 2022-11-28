<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\BondedWarehouse;
use SkylarkSoft\GoRMG\SystemSettings\Requests\BondedWarehouseRequest;

class BondedWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $items = BondedWarehouse::query()
        ->when($search, function($q) use($search) {
            $q->where('name', 'LIKE', '%' . $search . '%');
        })->latest()->paginate();

        return view('system-settings::bonded-warehouse.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BondedWarehouseRequest $request)
    {
        BondedWarehouse::query()->create($request->all());
        Session::flash('success', 'Data Created Successfully');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return BondedWarehouse::query()->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, BondedWarehouseRequest $request)
    {
        Session::flash('success', 'Data Updated Successfully');
        BondedWarehouse::query()->findOrFail($id)->update($request->all());

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BondedWarehouse::query()->findOrFail($id)->delete();
        Session::flash('success', 'Data Deleted Successfully');
        return redirect()->back();
    }

    //Json Bonded warehouse list
    public function fetch_warehouses()
    {
        $list = BondedWarehouse::all();
        return response()->json($list);
    }
}
