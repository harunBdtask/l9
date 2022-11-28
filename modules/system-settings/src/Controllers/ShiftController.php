<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ShiftRequest;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? '';
        $data['shifts'] = Shift::withoutGlobalScope('factoryId')
            ->join('factories', 'factories.id', 'shifts.factory_id')
            ->when($q != '', function ($query) use ($q) {
                return $query->orWhere('factories.factory_name', 'like', '%'.$q.'%')
                    ->orWhere('shifts.shift_name', 'like', '%'.$q.'%');
            })
            ->orderBy('shifts.created_at', 'DESC')
            ->select('shifts.*')
            ->paginate();
        $data['q'] = $q;

        return view('system-settings::pages.shifts', $data);
    }

    public function create()
    {
        $data['shifts'] = null;

        return view('system-settings::forms.shift', $data);
    }

    /**
     * @throws \Throwable
     */
    public function store(ShiftRequest $request, $id = null)
    {
        try {
            DB::beginTransaction();
            $shift_name = Shift::query()->findOrNew($id);
            $shift_name->fill($request->all())->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');
            return redirect('shifts');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Data Stored Failed!! Error: PT:101');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $shifts = Shift::findOrFail($id);

        return view('system-settings::forms.shift', compact('shifts'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $shifts = Shift::findOrFail($id);
            $shifts->delete();
            DB::commit();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('shifts');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!Error Code Prty.D-102');

            return redirect()->back();
        }
    }
}
