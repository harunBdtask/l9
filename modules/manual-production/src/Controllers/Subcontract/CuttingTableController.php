<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Subcontract;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractCuttingTable;
use SkylarkSoft\GoRMG\ManualProduction\Requests\SubcontractCuttingTableRequest;

class CuttingTableController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = SubcontractCuttingTable::query()
            ->with('subContractFactoryProfile', 'subContractCuttingFloor')
            ->when($search, function ($q) use ($search) {
                $q->where('floor_name', 'LIKE', '%' . $search . '%');
                $q->orWhere('responsible_person', 'LIKE', '%' . $search . '%');
                $q->orWhereHas('subContractFactoryProfile', function ($r) use ($search){
                    $r->where('name', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('status', 'desc')
            ->orderBy('id', 'desc')
            ->paginate();
        return view("manual-production::subcontract.cutting-table", compact('data', 'search'));
    }

    public function store(SubcontractCuttingTableRequest $request)
    {
        SubcontractCuttingTable::query()->create($request->all());
        Session::flash('success', 'Data Created Successfully');
        return redirect('/subcontract-cutting-table');
    }

    public function edit($id): JsonResponse
    {
        $profile = SubcontractCuttingTable::query()->findOrFail($id);
        $subcontract_factory_profile_option = "<option value=".$profile->subcontract_factory_profile_id.">".$profile->subContractFactoryProfile->name.'['.$profile->subContractFactoryProfile->factory->factory_name.']'."</option>";
        $subcontract_cutting_floor_option = "<option value=".$profile->subcontract_cutting_floor_id.">".$profile->subContractCuttingFloor->floor_name.'['.$profile->subContractCuttingFloor->factory->factory_name.']'."</option>";
        $data = $profile->toArray();
        $data['subcontract_factory_profile_option'] = $subcontract_factory_profile_option;
        $data['subcontract_cutting_floor_option'] = $subcontract_cutting_floor_option;
         return response()->json($data);
    }

    public function update($id, SubcontractCuttingTableRequest $request)
    {
        SubcontractCuttingTable::query()->findOrFail($id)->update($request->all());
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-cutting-table');
    }

    public function statusUpdate(SubcontractCuttingTable $cuttingTable)
    {
        $cuttingTable->update(['status' => !$cuttingTable->status]);
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-cutting-table');
    }

    public function destroy($id)
    {
        SubcontractCuttingTable::query()->findOrFail($id)->delete();
        Session::flash('success', 'Data Deleted Successfully');
        return redirect('/subcontract-cutting-table');
    }

}
