<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Subcontract;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractSewingLine;
use SkylarkSoft\GoRMG\ManualProduction\Requests\SubcontractSewingLineRequest;

class SewingLineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = SubcontractSewingLine::query()
            ->with('subContractFactoryProfile', 'subContractSewingFloor')
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
        return view("manual-production::subcontract.sewing-line", compact('data', 'search'));
    }

    public function store(SubcontractSewingLineRequest $request)
    {
        SubcontractSewingLine::query()->create($request->all());
        Session::flash('success', 'Data Created Successfully');
        return redirect('/subcontract-sewing-line');
    }

    public function edit($id): JsonResponse
    {
        $profile = SubcontractSewingLine::query()->findOrFail($id);
        $subcontract_factory_profile_option = "<option value=".$profile->subcontract_factory_profile_id.">".$profile->subContractFactoryProfile->name.'['.$profile->subContractFactoryProfile->factory->factory_name.']'."</option>";
        $subcontract_sewing_floor_option = "<option value=".$profile->subcontract_sewing_floor_id.">".$profile->subContractSewingFloor->floor_name.'['.$profile->subContractSewingFloor->factory->factory_name.']'."</option>";
        $data = $profile->toArray();
        $data['subcontract_factory_profile_option'] = $subcontract_factory_profile_option;
        $data['subcontract_sewing_floor_option'] = $subcontract_sewing_floor_option;
         return response()->json($data);
    }

    public function update($id, SubcontractSewingLineRequest $request)
    {
        SubcontractSewingLine::query()->findOrFail($id)->update($request->all());
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-sewing-line');
    }

    public function statusUpdate(SubcontractSewingLine $sewingLine)
    {
        $sewingLine->update(['status' => !$sewingLine->status]);
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-sewing-line');
    }

    public function destroy($id)
    {
        SubcontractSewingLine::query()->findOrFail($id)->delete();
        Session::flash('success', 'Data Deleted Successfully');
        return redirect('/subcontract-sewing-line');
    }

}
