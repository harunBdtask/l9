<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Subcontract;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFinishingTable;
use SkylarkSoft\GoRMG\ManualProduction\Requests\SubcontractFinishingTableRequest;

class FinishingTableController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = SubcontractFinishingTable::query()
            ->with('subContractFactoryProfile', 'subContractFinishingFloor')
            ->when($search, function ($q) use ($search) {
                $q->where('table_name', 'LIKE', '%' . $search . '%');
                $q->orWhere('responsible_person', 'LIKE', '%' . $search . '%');
                $q->orWhereHas('subContractFactoryProfile', function ($r) use ($search){
                    $r->where('name', 'LIKE', '%' . $search . '%');
                });
                $q->orWhereHas('subContractFinishingFloor', function ($r) use ($search){
                    $r->where('floor_name', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('status', 'desc')
            ->orderBy('id', 'desc')
            ->paginate();
        return view("manual-production::subcontract.finishing-table", compact('data', 'search'));
    }

    public function store(SubcontractFinishingTableRequest $request)
    {
        SubcontractFinishingTable::query()->create($request->all());
        Session::flash('success', 'Data Created Successfully');
        return redirect('/subcontract-finishing-table');
    }

    public function edit($id): JsonResponse
    {
        $profile = SubcontractFinishingTable::query()->findOrFail($id);
        $subcontract_factory_profile_option = "<option value=".$profile->subcontract_factory_profile_id.">".$profile->subContractFactoryProfile->name.'['.$profile->subContractFactoryProfile->factory->factory_name.']'."</option>";
        $subcontract_finishing_floor_option = "<option value=".$profile->subcontract_finishing_floor_id.">".$profile->subContractFinishingFloor->floor_name.'['.$profile->subContractFinishingFloor->factory->factory_name.']'."</option>";
        $data = $profile->toArray();
        $data['subcontract_factory_profile_option'] = $subcontract_factory_profile_option;
        $data['subcontract_finishing_floor_option'] = $subcontract_finishing_floor_option;
         return response()->json($data);
    }

    public function update($id, SubcontractFinishingTableRequest $request)
    {
        SubcontractFinishingTable::query()->findOrFail($id)->update($request->all());
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-finishing-table');
    }

    public function statusUpdate(SubcontractFinishingTable $finishingTable)
    {
        $finishingTable->update(['status' => !$finishingTable->status]);
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-finishing-table');
    }

    public function destroy($id)
    {
        SubcontractFinishingTable::query()->findOrFail($id)->delete();
        Session::flash('success', 'Data Deleted Successfully');
        return redirect('/subcontract-finishing-table');
    }
}
