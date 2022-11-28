<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\McInventory\Models\McMachine;
use SkylarkSoft\GoRMG\McInventory\Models\MachineType;
use SkylarkSoft\GoRMG\McInventory\Models\MachineUnit;
use SkylarkSoft\GoRMG\McInventory\Models\MachineBrand;
use SkylarkSoft\GoRMG\McInventory\Models\McMaintenance;
use SkylarkSoft\GoRMG\McInventory\Models\MachineSubType;
use SkylarkSoft\GoRMG\McInventory\Models\MachineLocation;
use SkylarkSoft\GoRMG\McInventory\Models\McMachineTransfer;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;

class MachineProfileController extends Controller
{
    public function index()
    {
         $list = McMachine::orderBy('id','desc')->with(['factory','brand','type','subtype'])->paginate(20);
        return view('McInventory::machine-modules.machine-profile.index', compact('list'));
    }

    public function create()
    {
        return view('McInventory::machine-modules.machine-profile.form');
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        try {

            $machine = McMachine::find($request->get('id'));
            $machine->name = $request->get('name');
            $machine->brand_id = $request->get('brand_id');
            $machine->model_no = $request->get('model_no');
            $machine->category_id = $request->get('category_id');
            $machine->type_id = $request->get('type_id');
            $machine->sub_type_id = $request->get('sub_type_id');
            $machine->origin = $request->get('origin');
            $machine->serial_no = $request->get('serial_no');
            $machine->location_id = $request->get('location_id');
            $machine->unit_id = $request->get('unit_id');
            $machine->description = $request->get('description');
            $machine->purchase_date = $request->get('purchase_date');
            $machine->last_maintenance = $request->get('last_maintenance');
            $machine->tenor = $request->get('tenor');
            $machine->next_maintenance = $request->get('next_maintenance');
            $machine->status = $request->get('status');
            $machine->save();

            return response()->json(['message' => ApplicationConstant::S_STORED]);
        } catch (\Exception $e) {
            return response()->json(['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {


            $machine = McMachine::find($id);
            $transfer = McMachineTransfer::where('machine_id', $id)->first();
            $maintenance = McMaintenance::where('machine_id', $id)->first();
            if(empty($transfer) && empty($maintenance)){
                $machine->delete();
                Session::flash('alert-success', 'Machine Deleted Successfully');
            }else{
                Session::flash('alert-danger', 'Unable to delete the item!');
            }

        } catch (\Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        }
        return redirect()->back();
    }

    public function fetchAllInfo()
    {
        try{
            $data['brands'] = MachineBrand::all();
            $data['categories11'] = McMachineInventoryConstant::MACHINE_CATEGORIES;
            $data['categories'] = constantSort(McMachineInventoryConstant::MACHINE_CATEGORIES);
            $data['types'] = MachineType::all();

            $barcode = request()->get('barcode')??null;
            if(isset($barcode) && !empty($barcode) && ($barcode!='undefined')){
                $machineProfile = McMachine::where('barcode',$barcode)->first();
                $data['subtypes'] = MachineSubType::where('machine_type',$machineProfile->type_id)->get();
            } else {
                $data['subtypes'] = MachineSubType::all();
            }
            $data['origins'] = constantSort(McMachineInventoryConstant::MACHINE_ORIGINS);
            $data['locations'] = MachineLocation::all();
            $data['units'] = (MachineUnit::all());
            $data['tenors'] = constantSort2(McMachineInventoryConstant::MACHINE_TENORS);
            $data['status'] = constantSort(McMachineInventoryConstant::MACHINE_STATUS);
            $data['mt_status'] = constantSort(McMachineInventoryConstant::MAINTENANCE_STATUS);
            $data['yes_no'] = constantSort(McMachineInventoryConstant::YES_NO);

            return response()->json($data, Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage(), Response::HTTP_BAD_REQUEST]);
        }



    }
    public function fetchMachineProfile($barcode)
    {
        try{
            $barcodeInfo = McMachine::where('barcode', $barcode)->first();
            return response()->json($barcodeInfo, Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage(), Response::HTTP_BAD_REQUEST]);
        }
    }

    public function getNextMaintenance(Request $request)
    {
        try{
            $last_maintenance = $request->get('last_maintenance');
            $tenor = $request->get('tenor');
            if ($last_maintenance){
                $next_maintenance = date('Y-m-d', strtotime($last_maintenance.'+'.$tenor.' days'));
            }
            return response()->json($next_maintenance, Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage(), Response::HTTP_BAD_REQUEST]);
        }
    }

    public function fetchMachineTypes($category_id)
    {
        try{
            $barcodeInfo = MachineType::where('machine_category', $category_id)->get();
            return response()->json($barcodeInfo, Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage(), Response::HTTP_BAD_REQUEST]);
        }
    }
    public function fetchMachineSubTypes($type_id)
    {
        try{
            $barcodeInfo = MachineSubType::where('machine_type', $type_id)->get();
            return response()->json($barcodeInfo, Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage(), Response::HTTP_BAD_REQUEST]);
        }
    }
}
