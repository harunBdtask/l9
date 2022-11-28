<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingMachine;
use SkylarkSoft\GoRMG\Skillmatrix\PackageConst;
use SkylarkSoft\GoRMG\Skillmatrix\Requests\SewingMachineRequest;

class SewingMachineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', null);
        $paginateNumber = intval($request->get('paginateNumber', 15));
        $sewingMachines = SewingMachine::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate($paginateNumber);

        return view(PackageConst::PACKAGE_NAME . '::pages.sewing_machines', [
            'sewingMachines' => $sewingMachines,
            'paginateNumber' => $paginateNumber,
        ]);
    }

    public function create()
    {
        return view(PackageConst::PACKAGE_NAME . '::forms.sewing_machine', [
            'sewingMachine' => null,
        ]);
    }

    public function store(SewingMachineRequest $request)
    {
        try {
            SewingMachine::create($request->all());
            Session::flash('success', S_SAVE_MSG);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return redirect('/sewing-machines');
        }
    }

    public function edit($id)
    {
        $sewingMachine = SewingMachine::findOrFail($id);

        return view(PackageConst::PACKAGE_NAME . '::forms.sewing_machine', [
            'sewingMachine' => $sewingMachine,
        ]);
    }

    public function update($id, SewingMachineRequest $request)
    {
        try {
            $sewingMachine = SewingMachine::findOrFail($id);
            $sewingMachine->update($request->all());
            Session::flash('success', S_UPDATE_MSG);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return redirect('/sewing-machines');
        }
    }

    public function destroy($id)
    {
        try {
            SewingMachine::destroy($id);
            Session::flash('success', S_DELETE_MSG);
        } catch (Exception $e) {
            Session::flash('error', "Something went wrong! " . $e->getMessage());
        } finally {
            return redirect('/sewing-machines');
        }
    }
}
