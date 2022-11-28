<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use PDF;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\McInventory\Models\McMachine;
use SkylarkSoft\GoRMG\McInventory\Models\McMachineTransfer;

class MachineTransferController extends Controller
{
    public function index()
    {
        $machineTransfers = McMachineTransfer::query()->orderBy('id','desc')
        ->with([
            'machine',
            'machineTransferFrom',
            'machineTransferTo'
        ])->paginate();
        return view('McInventory::machine-modules.machine-transfer.index',[
            'machineTransfers' => $machineTransfers
        ]);
    }

    public function create()
    {
        return view('McInventory::machine-modules.machine-transfer.form');
    }

    public function saveTransfer(Request $request)
    {
        $request->validate([
            'transfer_to' => 'required'
        ]);
        try {


            $machine = McMachine::find($request->get('mc_id'));

            $mt = new McMachineTransfer();
            $mt->machine_id = $request->get('mc_id');
            $mt->transfer_from = $request->get('transfer_from');
            $mt->transfer_to = $request->get('transfer_to');
            $mt->reason = $request->get('reason');
            $mt->attention = $request->get('attention');
            $mt->contact_no = $request->get('contact_no');
            $mt->save();

            $machine->location_id = $mt->transfer_to;
            $machine->save();

            return response()->json(['message' => ApplicationConstant::S_STORED]);
        } catch (\Exception $e) {
            return response()->json(['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function edit($id)
    {
       try{
            $machineTransfer = McMachineTransfer::query()->findOrFail($id);
            $machine = McMachine::query()->findOrFail($machineTransfer->machine_id);

            return response()->json([
                'data' => $machineTransfer,
                'machine' => $machine,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
       } catch(\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
       }
    }

    public function update(Request $request,$id)
    {
        try {
            $machine = McMachine::findOrFail($request->get('machine_id'));

            $machineTransfer = McMachineTransfer::query()->findOrFail($id);
            $machineTransfer->machine_id = $request->get('machine_id');
            $machineTransfer->transfer_from = $request->get('transfer_from');
            $machineTransfer->transfer_to = $request->get('transfer_to');
            $machineTransfer->reason = $request->get('reason');
            $machineTransfer->attention = $request->get('attention');
            $machineTransfer->contact_no = $request->get('contact_no');
            $machineTransfer->save();

            $machine->location_id = $machineTransfer->transfer_to;
            $machine->save();

            return response()->json(['message' => ApplicationConstant::S_UPDATED]);
        } catch (\Exception $e) {
            return response()->json(['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            McMachineTransfer::query()->findOrFail($id)->delete();
            DB::commit();
            Session::flash('success', S_DEL_MSG);
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function view($id)
    {
        $transfer = McMachineTransfer::query()
            ->with([
                'machineTransferTo',
                'machine.type',
                'machine.subtype',
                'machine.brand'
            ])
            ->findOrFail($id);
        return view('McInventory::machine-modules.machine-transfer.view',[
            'transfer' => $transfer
        ]);
    }

    public function pdf($id)
    {
        $transfer = McMachineTransfer::query()
            ->with([
                'machineTransferTo',
                'machine.type',
                'machine.subtype',
                'machine.brand'
            ])
            ->findOrFail($id);

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('McInventory::machine-modules.machine-transfer.pdf', [
                'transfer' => $transfer,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('machine_transfer.pdf');
    }

}
