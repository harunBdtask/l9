<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\McInventory\Models\McMachine;
use SkylarkSoft\GoRMG\McInventory\Models\McMaintenance;
use SkylarkSoft\GoRMG\McInventory\Constants\McMachineInventoryConstant;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = McMaintenance::query()->orderBy('id','desc')
                        ->with([
                            'machine',
                            'machineUnit'
                        ])
                        ->paginate();
        return view('McInventory::machine-modules.maintenance.index', compact('maintenances'));
    }

    public function create()
    {
        return view('McInventory::machine-modules.maintenance.form');
    }

    public function saveMaintenance(Request $request): JsonResponse
    {
        $request->validate([
            'mt_status' => 'required'
        ]);
        try {

            $machine = McMachine::find($request->get('mc_id'));

            $mt = new McMaintenance();
            if ($request->get('id')) {
                $mt = McMaintenance::find($request->get('id'));
            }
            $mt->machine_id = $request->get('mc_id');

            $mt->last_maintenance = date('Y-m-d');
            $mt->tenor = $machine->tenor??McMachineInventoryConstant::MACHINE_TENORS[0];
            $mt->next_maintenance = date('Y-m-d', strtotime(date('Y-m-d').' + '.$mt->tenor.' days'));

            $mt->status = $request->get('mt_status');
            $mt->description = $request->get('mt_description');
            $mt->parts_change = $request->get('mt_parts_change');
            $mt->parts_change_description = $request->get('mt_parts_change_description');
            $mt->mechanic = $request->get('mt_mechanic');
            $mt->save();

            $machine->last_maintenance = $request->get('last_maintenance');
            $machine->next_maintenance = $request->get('next_maintenance');
            $machine->save();

            return response()->json(['barcode' => $machine->barcode, 'id' => $mt->id], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function getData(McMaintenance $mcMaintenance)
    {
        try {
            return response()->json($mcMaintenance, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            McMaintenance::query()->findOrFail($id)->delete();
            DB::commit();
            Session::flash('alert-success', 'Item Deleted Successfully');
        } catch (Exception $exception) {
            Session::flash('alert-error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
