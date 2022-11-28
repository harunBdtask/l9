<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\RackCartonPosition;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseFloor;
use SkylarkSoft\GoRMG\WarehouseManagement\Models\WarehouseRack;
use SkylarkSoft\GoRMG\WarehouseManagement\Requests\WarehouseRackRequest;

class WarehouseRackController extends Controller
{
    public function index()
    {
        $warehouse_racks = WarehouseRack::orderBy('id', 'DESC')->paginate();

        return view('warehouse-management::pages.warehouse_racks', ['warehouse_racks' => $warehouse_racks]);
    }

    public function create()
    {
        $warehouse_floors = WarehouseFloor::pluck('name', 'id');

        return view('warehouse-management::forms.warehouse_rack', [
            'warehouse_rack' => null,
            'warehouse_floors' => $warehouse_floors,
        ]);
    }

    public function store(WarehouseRackRequest $request)
    {
        try {
            DB::beginTransaction();
            $rack_name = $request->name;
            $warehouse_floor_id = $request->warehouse_floor_id;
            $rack_capacity = $request->capacity;

            $warehouse_rack = new WarehouseRack();
            $warehouse_rack->name = $rack_name;
            $warehouse_rack->warehouse_floor_id = $warehouse_floor_id;
            $warehouse_rack->capacity = $rack_capacity;
            $warehouse_rack->save();

            for ($i = 1; $i <= $rack_capacity; $i++) {
                $rack_carton_postion = new RackCartonPosition();
                $rack_carton_postion->warehouse_floor_id = $warehouse_floor_id;
                $rack_carton_postion->warehouse_rack_id = $warehouse_rack->id;
                $rack_carton_postion->position_no = $i;
                $rack_carton_postion->save();
            }

            DB::commit();
            Session::flash('alert-success', 'Data stored successfully!!');

            return redirect('/warehouse-racks');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('alert-danger', 'Something went wrong!! Try again!!');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $warehouse_rack = WarehouseRack::findOrFail($id);
        $warehouse_floors = WarehouseFloor::pluck('name', 'id');

        return view('warehouse-management::forms.warehouse_rack', [
            'warehouse_rack' => $warehouse_rack,
            'warehouse_floors' => $warehouse_floors,
        ]);
    }

    public function update($id, WarehouseRackRequest $request)
    {
        try {
            DB::beginTransaction();

            $rack_carton_postion_query = RackCartonPosition::where('warehouse_rack_id', $id);
            $is_carton_exists = 0;
            if ($rack_carton_postion_query->count()) {
                foreach ($rack_carton_postion_query->get() as $rack_carton_postion) {
                    if ($rack_carton_postion->warehouse_carton_id) {
                        $is_carton_exists = 1;

                        break;
                    }
                }
                if ($is_carton_exists) {
                    Session::flash('alert-danger', 'Cannot change this rack information because carton exist in this rack!!');

                    return redirect()->back();
                } else {
                    $rack_carton_postion_query->forceDelete();
                }
            }

            $warehouse_floor_id = $request->warehouse_floor_id;
            $rack_capacity = $request->capacity;

            $warehouse_rack = WarehouseRack::findOrFail($id);
            $warehouse_rack->update($request->all());

            for ($i = 1; $i <= $rack_capacity; $i++) {
                $rack_carton_postion = new RackCartonPosition();
                $rack_carton_postion->warehouse_floor_id = $warehouse_floor_id;
                $rack_carton_postion->warehouse_rack_id = $warehouse_rack->id;
                $rack_carton_postion->position_no = $i;
                $rack_carton_postion->save();
            }

            DB::commit();
            Session::flash('alert-success', 'Data updated successfully!!');

            return redirect('/warehouse-racks');
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
            $rack_carton_postion_query = RackCartonPosition::where('warehouse_rack_id', $id);
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
                Session::flash('alert-danger', 'Cannot delete this rack because carton exist in this rack!!');

                return redirect()->back();
            }
            $warehouse_rack = WarehouseRack::findOrFail($id);
            $warehouse_rack->delete();
            DB::commit();
            Session::flash('alert-success', 'Data deleted successfully!!');

            return redirect('/warehouse-racks');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('alert-danger', 'Something went wrong!! Try again!!');

            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        if ($request->q == '') {
            return redirect('/warehouse-racks');
        }
        $warehouse_racks = WarehouseRack::withoutGlobalScope('factoryId')->with('warehouseFloor')
            ->leftJoin('warehouse_floors', 'warehouse_floors.id', 'warehouse_racks.warehouse_floor_id')
            ->where('warehouse_racks.factory_id', factoryId())
            ->where(function ($query) use ($request) {
                $query->orWhere('warehouse_racks.name', 'like', '%' . $request->q . '%')
                    ->orWhere('warehouse_floors.name', 'like', '%' . $request->q . '%');
            })
            ->select('warehouse_racks.*')
            ->orderBy('warehouse_racks.id', 'DESC')->paginate();

        return view('warehouse-management::pages.warehouse_racks', ['warehouse_racks' => $warehouse_racks, 'q' => $request->q]);
    }

    public function getWarehouseRacks($warehouse_floor_id)
    {
        return WarehouseRack::where('warehouse_floor_id', $warehouse_floor_id)->pluck('name', 'id');
    }
}
