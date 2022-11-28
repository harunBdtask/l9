<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnittingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Requests\KnittingFloorRequest;
use Throwable;

class KnittingFloorController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? '';
        $knittingFloor = KnittingFloor::query()
            ->when($q, function ($query) use ($q) {
                $query->where('name', $q);
            })
            ->orderByDesc('id')
            ->paginate();

        return view('system-settings::pages.knitting_floor', compact('knittingFloor'));
    }

    public function create()
    {
        $knittingFloor = null;

        return view('system-settings::forms.knitting_floor', compact('knittingFloor'));
    }

    /**
     * @throws Throwable
     */
    public function store(KnittingFloorRequest $request): RedirectResponse
    {
        $id = $request->id ?? '';

        try {
            DB::beginTransaction();
            $operator = KnittingFloor::query()->findOrNew($id);
            $operator->fill($request->all())->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Data Stored Failed!!');
        }

        return redirect('/knitting-floor');
    }

    public function edit($id)
    {
        $knittingFloor = KnittingFloor::query()->find($id);

        return view('system-settings::forms.knitting_floor', compact('knittingFloor'));
    }

    /**
     * @throws Throwable
     */
    public function destroy($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $operators = KnittingFloor::query()->findOrFail($id);
            $operators->delete();
            DB::commit();
            Session::flash('alert-success', 'Data Deleted Successfully!!');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');
        }

        return redirect()->back();
    }
}
