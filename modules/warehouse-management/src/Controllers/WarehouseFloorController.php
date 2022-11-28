<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\RackCartonPosition;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseFloor;
use SkylarkSoft\GoRMG\WarehouseManagement\Requests\WarehouseFloorRequest;

class WarehouseFloorController extends Controller
{
    public function index()
    {
        $warehouse_floors = WarehouseFloor::orderBy('id', 'DESC')->paginate();

        return view('warehouse-management::pages.warehouse_floors', ['warehouse_floors' => $warehouse_floors]);
    }

    public function create()
    {
        return view('warehouse-management::forms.warehouse_floor', ['warehouse_floor' => null]);
    }

    public function store(WarehouseFloorRequest $request)
    {
        try {
            DB::beginTransaction();
            WarehouseFloor::create($request->all());
            DB::commit();
            Session::flash('alert-success', 'Data stored successfully!!');

            return redirect('/warehouse-floors');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('alert-danger', 'Something went wrong!! Try again!!');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $warehouse_floor = WarehouseFloor::findOrFail($id);

        return view('warehouse-management::forms.warehouse_floor', ['warehouse_floor' => $warehouse_floor]);
    }

    public function update($id, WarehouseFloorRequest $request)
    {
        try {
            DB::beginTransaction();
            $warehouse_floor = WarehouseFloor::findOrFail($id);
            $warehouse_floor->update($request->all());
            DB::commit();
            Session::flash('alert-success', 'Data updated successfully!!');

            return redirect('/warehouse-floors');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('alert-danger', 'Something went wrong!! Try again!!');

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            //If in rack_carton_positions table is free(null) of warehouse_carton_id then delete can occur
            $rack_carton_postion_query = RackCartonPosition::where('warehouse_floor_id', $id);
            $is_carton_exists = 0;
            if ($rack_carton_postion_query->count()) {
                foreach ($rack_carton_postion_query->get() as $rack_carton_postion) {
                    if ($rack_carton_postion->warehouse_carton_id) {
                        $is_carton_exists = 1;

                        break;
                    }
                }
            }
            if ($is_carton_exists) {
                Session::flash('alert-danger', 'Cannot delete this floor because carton exist in this floor!!');

                return redirect()->back();
            }
            $warehouse_floor = WarehouseFloor::findOrFail($id);
            $warehouse_floor->delete();
            DB::commit();
            Session::flash('alert-success', 'Data deleted successfully!!');

            return redirect('/warehouse-floors');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('alert-danger', 'Something went wrong!! Try again!!');

            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        if ($request->q == '') {
            return redirect('/warehouse-floors');
        }
        $warehouse_floors = WarehouseFloor::where('name', 'like', '%' . $request->q . '%')
            ->orderBy('id', 'DESC')->paginate();

        return view('warehouse-management::pages.warehouse_floors', ['warehouse_floors' => $warehouse_floors, 'q' => $request->q]);
    }
}
