<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Subcontract;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractEmbellishmentFloor;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile;
use SkylarkSoft\GoRMG\ManualProduction\Requests\SubcontractEmbellishmentFloorRequest;

class EmbellishmentFloorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = SubcontractEmbellishmentFloor::query()
            ->with('subContractFactoryProfile')
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
        return view("manual-production::subcontract.embellishment-floor", compact('data', 'search'));
    }

    public function store(SubcontractEmbellishmentFloorRequest $request)
    {
        $this->validation($request);
        SubcontractEmbellishmentFloor::query()->create($request->all());
        Session::flash('success', 'Data Created Successfully');
        return redirect('/subcontract-embellishment-floor');
    }

    public function edit($id): JsonResponse
    {
        $profile = SubcontractEmbellishmentFloor::query()->findOrFail($id);
        $subcontract_factory_profile_option = "<option value=".$profile->subcontract_factory_profile_id.">".$profile->subContractFactoryProfile->name.'['.$profile->subContractFactoryProfile->factory->factory_name.']'."</option>";
        $data = $profile->toArray();
        $data['subcontract_factory_profile_option'] = $subcontract_factory_profile_option;
        return response()->json($data);
    }

    public function update($id, SubcontractEmbellishmentFloorRequest $request)
    {
        $this->validation($request);
        SubcontractEmbellishmentFloor::query()->findOrFail($id)->update($request->all());
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-embellishment-floor');
    }

    public function statusUpdate(SubcontractEmbellishmentFloor $embellishmentFloor)
    {
        $embellishmentFloor->update(['status' => !$embellishmentFloor->status]);
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-embellishment-floor');
    }

    public function destroy($id)
    {
        SubcontractEmbellishmentFloor::query()->findOrFail($id)->delete();
        Session::flash('success', 'Data Deleted Successfully');
        return redirect('/subcontract-embellishment-floor');
    }

    private function validation($request){
        $request->validate([
            'subcontract_factory_profile_id' => 'required|numeric',
            'floor_name' => 'required',
            'responsible_person' => 'required',
        ]);
    }
}
