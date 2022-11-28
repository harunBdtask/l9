<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Controllers\Subcontract;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\ManualProduction\Models\Subcontract\SubcontractFactoryProfile;
use SkylarkSoft\GoRMG\ManualProduction\Requests\SubcontractFactoryProfileRequest;

class FactoryProfileController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $data = SubcontractFactoryProfile::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
                $q->orWhere('short_name', 'LIKE', '%' . $search . '%');
                $q->orWhere('responsible_person', 'LIKE', '%' . $search . '%');
                $q->orWhere('email', 'LIKE', '%' . $search . '%');
                $q->orWhere('contact_no', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('status', 'desc')
            ->orderBy('id', 'desc')
            ->paginate();
        $types = SubcontractFactoryProfile::OPERATION_TYPE;
        return view("manual-production::subcontract.factory-profile.index", compact('data', 'types', 'search'));
    }

    public function create()
    {
        $profile = null;
        $types = SubcontractFactoryProfile::OPERATION_TYPE;
        return view("manual-production::subcontract.factory-profile.create-update", compact('profile', 'types'));
    }

    public function store(SubcontractFactoryProfileRequest $request)
    {
        SubcontractFactoryProfile::query()->create($request->all());
        Session::flash('success', 'Data Created Successfully');
        return redirect('/subcontract-factory-profile');
    }

    public function edit($id)
    {
        $profile = SubcontractFactoryProfile::query()->findOrFail($id);
        $types = SubcontractFactoryProfile::OPERATION_TYPE;
        return view("manual-production::subcontract.factory-profile.create-update", compact('profile', 'types'));
    }

    public function update($id, SubcontractFactoryProfileRequest $request)
    {
        SubcontractFactoryProfile::query()->findOrFail($id)->update($request->all());
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-factory-profile');
    }

    public function statusUpdate(SubcontractFactoryProfile $factoryProfile)
    {
        $factoryProfile->update(['status' => !$factoryProfile->status]);
        Session::flash('success', 'Data Updated Successfully');
        return redirect('/subcontract-factory-profile');
    }

    public function destroy($id)
    {
        SubcontractFactoryProfile::query()->findOrFail($id)->delete();
        Session::flash('success', 'Data Deleted Successfully');
        return redirect('/subcontract-factory-profile');
    }

}
