<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ShiftRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnitFabricFaultSetting;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnitFabricGradeSetting;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueKnitFabricFaultName;

class KnitFabricFaultSettingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $knit_fabric_fault = KnitFabricFaultSetting::query()
            ->with('factory')
            ->when($search, function ($query) use ($search) {
                $query->where('sequence', $search)
                    ->orWhere('name', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('system-settings::pages.knit_fabric_fault_setting', compact('knit_fabric_fault'));
    }

    public function create()
    {
        $knit_fabric_fault = null;

        return view('system-settings::forms.knit_fabric_fault_setting', compact('knit_fabric_fault'));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request, $id = null)
    {
        $request->validate([
            'sequence' => 'required',
            'name' => ['required',"not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", new UniqueKnitFabricFaultName()],
            'status' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $shift_name = KnitFabricFaultSetting::query()->findOrNew($id);
            $shift_name->fill($request->all())->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('knit_fabric_fault_settings');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Data Stored Failed!!');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $knit_fabric_fault = KnitFabricFaultSetting::query()->findOrFail($id);

        return view('system-settings::forms.knit_fabric_fault_setting', compact('knit_fabric_fault'));
    }

    /**
     * @throws Throwable
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $knit_fabric_fault = KnitFabricFaultSetting::query()->findOrFail($id);
            $knit_fabric_fault->delete();
            DB::commit();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('knit_fabric_fault_settings');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
