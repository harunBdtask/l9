<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnitFabricGradeSetting;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ShiftRequest;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueKnitFabricGrade;
use Throwable;

class KnitFabricGradeSettingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $knit_fabric_grade = KnitFabricGradeSetting::query()
            ->with('factory')
            ->when($search, function ($query) use ($search) {
                $query->where('grade', $search);
            })
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('system-settings::pages.knit_fabric_grade_setting', compact('knit_fabric_grade'));
    }

    public function create()
    {
        $knit_fabric_grade = null;

        return view('system-settings::forms.knit_fabric_grade_setting', compact('knit_fabric_grade'));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request, $id = null)
    {
        $request->validate([
            'from' => 'required|numeric',
            'to' => 'required|numeric',
            'grade' => ['required', new UniqueKnitFabricGrade()],
            'status' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $shift_name = KnitFabricGradeSetting::query()->findOrNew($id);
            $shift_name->fill($request->all())->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('knit_fabric_grade_settings');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Data Stored Failed!!');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $knit_fabric_grade = KnitFabricGradeSetting::query()->findOrFail($id);

        return view('system-settings::forms.knit_fabric_grade_setting', compact('knit_fabric_grade'));
    }

    /**
     * @throws Throwable
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $knit_fabric_grade = KnitFabricGradeSetting::query()->findOrFail($id);
            $knit_fabric_grade->delete();
            DB::commit();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('knit_fabric_grade_settings');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
