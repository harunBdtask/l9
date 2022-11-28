<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Requests\FabricConstructionEntryRequest;
use Symfony\Component\HttpFoundation\Response;

class FabricConstructionEntryController extends Controller
{
    public function index()
    {
        $fabricConstructions = FabricConstructionEntry::query()->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.fabric_construction_entry', compact('fabricConstructions'));
    }

    public function store(FabricConstructionEntryRequest $request)
    {
        try {
            $data = FabricConstructionEntry::create($request->all());

            if (Request::capture()->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
            }

            Session::flash('success', 'Data Stored Successfully!!');
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong');
        }

        return redirect('/fabric-construction-entry');
    }

    public function show($id)
    {
        return FabricConstructionEntry::findOrFail($id);
    }

    public function update($id, FabricConstructionEntryRequest $request)
    {
        try {
            FabricConstructionEntry::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated Successfully!!');
        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong');
        }

        return redirect('/fabric-construction-entry');
    }

    public function destroy($id)
    {
        $fabric_construction = FabricConstructionEntry::findOrFail($id);
        $checkValue = NewFabricComposition::query()->where('construction', $fabric_construction->construction_name)->first();

        if (! isset($checkValue)) {
            $fabric_construction->delete();
            Session::flash('error', 'Data Deleted Successfully');
        } else {
            Session::flash('error', 'Can Not Delete! data already attached in Fabric Compositions');
        }

        return redirect('/fabric-construction-entry');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $fabricConstructions = FabricConstructionEntry::where('construction_name', 'like', '%' . $search . '%')->paginate();

        return view('system-settings::pages.fabric_construction_entry', compact('fabricConstructions', 'search'));
    }


    public function fabricConstructions()
    {
        try {
            $data = FabricConstructionEntry::select('id', 'construction_name')->get();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
