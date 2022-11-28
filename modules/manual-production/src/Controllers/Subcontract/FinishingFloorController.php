<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Subcontract;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFinishingFloor;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile;
use SkylarkSoft\GoRMG\ManualProduction\Requests\SubcontractFinishingFloorRequest;

class FinishingFloorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = SubcontractFinishingFloor::query()
            ->when($search, function ($q) use ($search) {
                $q->where('floor_name', 'LIKE', '%' . $search . '%');
                $q->orWhere('responsible_person', 'LIKE', '%' . $search . '%');
                $q->orWhereHas('subContractFactoryProfile', function ($r) use ($search) {
                    $r->where('name', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('status', 'desc')
            ->orderBy('id', 'desc')
            ->paginate();
        return view("manual-production::subcontract.finishing-floor", compact('data', 'search'));
    }

    public function store(SubcontractFinishingFloorRequest $request)
    {
        SubcontractFinishingFloor::query()->create($request->all());
        Session::flash('success', 'Data Created Successfully');
        return redirect('/subcontract-finishing-floor');
    }

    public function edit($id): JsonResponse
    {
        $profile = SubcontractFinishingFloor::query()->findOrFail($id);
        $subcontract_factory_profile_option = "<option value=".$profile->subcontract_factory_profile_id.">".$profile->subContractFactoryProfile->name.'['.$profile->subContractFactoryProfile->factory->factory_name.']'."</option>";
        $data = $profile->toArray();
        $data['subcontract_factory_profile_option'] = $subcontract_factory_profile_option;
        return response()->json($data);
    }

    public function update($id, SubcontractFinishingFloorRequest $request)
    {
        SubcontractFinishingFloor::query()->findOrFail($id)->update($request->all());
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-finishing-floor');
    }

    public function statusUpdate(SubcontractFinishingFloor $finishingFloor)
    {
        $finishingFloor->update(['status' => !$finishingFloor->status]);
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-finishing-floor');
    }

    public function destroy($id)
    {
        SubcontractFinishingFloor::query()->findOrFail($id)->delete();
        Session::flash('success', 'Data Deleted Successfully');
        return redirect('/subcontract-finishing-floor');
    }

}
