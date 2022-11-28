<?php

namespace SkylarkSoft\GoRMG\McInventory\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\McInventory\Models\McMachine;
use SkylarkSoft\GoRMG\McInventory\Models\McBarcodeGeneration;


class MachineBarcodeGenerationController extends Controller
{
    public function index()
    {
        $list = McBarcodeGeneration::with(['factory'])->orderBy('id','desc')->paginate(15);
        return view('McInventory::machine-modules.machine-barcode-generation.index', compact('list'));
    }

    public function create()
    {
        return view('McInventory::machine-modules.machine-barcode-generation.form');
    }
    public function print(Request $request)
    {
        $id = $request->get('id');
        $machines = McMachine::where('barcode_generation_id', $request->get('id'))->get();
        return view('McInventory::machine-modules.machine-barcode-generation.print', compact('id','machines'));
    }

    public function store(Request $request): JsonResponse
    {

        $this->validateRequest($request);
        try {

            $bg = new McBarcodeGeneration();
            $bg->factory_id = $request->get('factory_id');
            $bg->no_of_machine = $request->get('no_of_machine');
            $bg->save();
            $bg_id = $bg->id;

            if($request->get('no_of_machine') > 0)
            {
                $machines =[];
                $max = McMachine::max('barcode')??10000;
                for($i=1; $i<= $request->get('no_of_machine'); $i++)
                {
                    $barcode = $max + $i;
                    $item = [
                        'name' => 'Demo MC-'.$i,
                        'barcode' => $barcode,
                        'barcode_generation_id' => $bg_id,
                        'factory_id' => $request->get('factory_id'),
                        'status' => 1
                    ];
                    array_push($machines, $item);
                }
                McMachine::insert($machines);
            }

            return response()->json(['message' => ApplicationConstant::S_STORED, 'bg_id' => $bg_id ]);
        } catch (\Exception $e) {
            return response()->json(['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function validateRequest(Request $request)
    {
        return $request->validate([
            'factory_id' => 'required',
            'no_of_machine' => 'required'
        ]);
    }

    public function fetchBarcodeGenerator(McBarcodeGeneration $McBarcodeGeneration)
    {
        try{

            $data['barcodeInfo'] = $McBarcodeGeneration;
            $data['barcodeList'] = McMachine::where('barcode_generation_id', $McBarcodeGeneration->id)->get();
            return response()->json($data, Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return response()->json(['errMsg' => $e->getMessage(), Response::HTTP_BAD_REQUEST]);
        }

    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        try {
            $barcode = McBarcodeGeneration::findOrFail($id);

            DB::beginTransaction();
            $barcode->machine()->delete();
            $barcode->delete();
            DB::commit();

            Session::flash('alert-success', 'Deleted Successfully!');

        } catch (\Exception $e) {
            Session::flash('alert-danger', 'Something Went Wrong!');
        }

        return redirect()->back();
    }

}
