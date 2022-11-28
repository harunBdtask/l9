<?php

namespace SkylarkSoft\GoRMG\TQM\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use SkylarkSoft\GoRMG\TQM\Models\TqmDhuLevel;
use SkylarkSoft\GoRMG\TQM\Requests\TqmDhuLevelsRequest;

class TqmDhuLevelsController extends Controller
{
    /**
     * Get Defects List and search in the list
     *
     * @return View
     */
    public function index(): View
    {
        $search = request('search') ?? null;
        $dhuLevels = TqmDhuLevel::getList($search);

        return view('tqm::dhu-levels.list', compact('dhuLevels'));
    }

    /**
     * Generate Defects Form create
     *
     * @return View
     */
    public function create(): View
    {
        return view('tqm::dhu-levels.form', [
            'factory_options' => Cache::get('factories') ?? [],
            'sections' => TqmDhuLevel::SECTIONS,
            'comparison_statuses' => TqmDhuLevel::COMPARISON_STATUSES,
            'dhuLevel' => null,
        ]);
    }

    /**
     * Store Defect data
     * @param TqmDhuLevelsRequest $request
     * @param TqmDhuLevel $dhuLevel
     * @return RedirectResponse
     */
    public function store(TqmDhuLevelsRequest $request, TqmDhuLevel $dhuLevel): RedirectResponse
    {
        try {
            $dhuLevel->fill($request->all())->save();
            Session::flash('alert-success', \S_SAVE_MSG);
            return redirect('tqm-dhu-levels');
        } catch (Exception $e) {
            Session::flash('alert-danger', \SOMETHING_WENT_WRONG . ' ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Generate DHU levels Form Edit
     *
     * @param TqmDhuLevel $dhuLevel
     * @return View
     */
    public function edit(TqmDhuLevel $dhuLevel): View
    {
        return view('tqm::dhu-levels.form', [
            'factory_options' => Cache::get('factories') ?? [],
            'sections' => TqmDhuLevel::SECTIONS,
            'comparison_statuses' => TqmDhuLevel::COMPARISON_STATUSES,
            'dhuLevel' => $dhuLevel,
        ]);
    }

    /**
     * Update DHU levels data
     * @param TqmDhuLevelsRequest $request
     * @param TqmDhuLevel $dhuLevel
     * @return RedirectResponse
     */
    public function update(TqmDhuLevelsRequest $request, TqmDhuLevel $dhuLevel): RedirectResponse
    {
        try {
            $dhuLevel->fill($request->all())->save();
            Session::flash('alert-success', \S_UPDATE_MSG);
            return redirect('tqm-dhu-levels');
        } catch (Exception $e) {
            Session::flash('alert-danger', \SOMETHING_WENT_WRONG . ' ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Delete DHU levels data
     * @param TqmDhuLevel $dhuLevel
     *
     * @return RedirectResponse
     */
    public function destroy(TqmDhuLevel $dhuLevel): RedirectResponse
    {
        try {
            $dhuLevel->delete();
            Session::flash('alert-success', \S_DELETE_MSG);
        } catch (Exception $e) {
            Session::flash('alert-danger', \SOMETHING_WENT_WRONG . ' ' . $e->getMessage());
        }
        return redirect()->back();
    }
}
