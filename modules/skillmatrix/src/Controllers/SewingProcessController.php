<?php

namespace SkylarkSoft\GoRMG\Skillmatrix\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Skillmatrix\Models\SewingProcess;
use SkylarkSoft\GoRMG\Skillmatrix\PackageConst;
use SkylarkSoft\GoRMG\Skillmatrix\Requests\SewingProcessRequest;

class SewingProcessController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', null);
        $paginateNumber = intval($request->get('paginateNumber', 15));
        $processes = SewingProcess::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate($paginateNumber);

        return view(PackageConst::PACKAGE_NAME . '::pages.processes', [
            'processes' => $processes,
            'paginateNumber' => $paginateNumber,
        ]);
    }

    public function create()
    {
        return view(PackageConst::PACKAGE_NAME . '::forms.process', ['process' => null]);
    }

    public function store(SewingProcessRequest $request)
    {
        try {
            SewingProcess::create($request->all());
            Session::flash('success', S_SAVE_MSG);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        } finally {
            return redirect('/sewing-processes');
        }
    }

    public function details($id): \Illuminate\Http\JsonResponse
    {
        $processData = SewingProcess::findOrFail($id);

        return response()->json(['process_data' => $processData]);
    }

    public function edit($id)
    {
        $process = SewingProcess::findOrFail($id);

        return view(PackageConst::PACKAGE_NAME . '::forms.process', ['process' => $process]);
    }

    public function update($id, SewingProcessRequest $request)
    {
        try {
            $process = SewingProcess::findOrFail($id);
            $process->update($request->all());
            Session::flash('success', S_UPDATE_MSG);
        } catch (Exception $e) {
            Session::flash('error', \SOMETHING_WENT_WRONG . " " . $e->getMessage());
        } finally {
            return redirect('/sewing-processes');
        }
    }

    public function destroy($processId)
    {
        try {
            SewingProcess::destroy($processId);
            Session::flash('success', S_DELETE_MSG);
        } catch (Exception $e) {
            Session::flash('error', \SOMETHING_WENT_WRONG . " " . $e->getMessage());
        } finally {
            return redirect('/sewing-processes');
        }
    }
}
