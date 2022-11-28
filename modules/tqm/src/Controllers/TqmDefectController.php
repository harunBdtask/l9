<?php

namespace SkylarkSoft\GoRMG\TQM\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use SkylarkSoft\GoRMG\TQM\Models\TqmDefect;
use SkylarkSoft\GoRMG\TQM\Requests\TqmDefectRequest;
use Symfony\Component\HttpFoundation\Response;

class TqmDefectController extends Controller
{
    /**
     * Get Defects List and search in the list
     *
     * @return Illuminate\View\View
     */
    public function index(): View
    {
        $search = request()->get('search') ?? null;

        $defects = TqmDefect::getList($search);

        return view('tqm::defects.list', [
            'defects' => $defects
        ]);
    }

    /**
     * Generate Defects Form form create
     *
     * @return View
     */
    public function create(): View
    {
        return view('tqm::defects.form', [
            'factory_options' => Cache::get('factories') ?? [],
            'sections' => TqmDefect::SECTIONS,
            'defect' => null,
        ]);
    }

    /**
     * Store Defect data
     * @param TqmDefectRequest $request
     *
     * @return RedirectResponse
     */
    public function store(TqmDefectRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $defect = new TqmDefect();
            $defect->fill($request->except('_token'));
            $defect->save();
            DB::commit();
            Session::flash('alert-success', \S_SAVE_MSG);
            return \redirect('tqm-defects');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('alert-danger', \SOMETHING_WENT_WRONG .' '. $e->getMessage());
            return \redirect()->back();
        }
    }

    /**
     * Generate Defects Form form edit
     *
     * @return View
     */
    public function edit(TqmDefect $defect): View
    {
        return view('tqm::defects.form', [
            'factory_options' => Cache::get('factories') ?? [],
            'sections' => TqmDefect::SECTIONS,
            'defect' => $defect,
        ]);
    }

    /**
     * Update Defect data
     * @param TqmDefectRequest $request
     *
     * @return RedirectResponse
     */
    public function update(TqmDefect $defect, TqmDefectRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $defect->fill($request->except('_token'));
            $defect->save();
            DB::commit();
            Session::flash('alert-success', \S_UPDATE_MSG);
            return \redirect('tqm-defects');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('alert-danger', \SOMETHING_WENT_WRONG .' '. $e->getMessage());
            return \redirect()->back();
        }
    }

    /**
     * Delete Defect data
     * @param TqmDefect $defect
     *
     * @return RedirectResponse
     */
    public function destroy(TqmDefect $defect): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $defect->delete();
            DB::commit();
            Session::flash('alert-success', \S_DELETE_MSG);
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('alert-danger', \SOMETHING_WENT_WRONG .' '. $e->getMessage());
        }
        return \redirect()->back();
    }

    /**
     * Fetch Defects for select field options
     *
     * @return JsonResponse
     */
    public function fetchDefectsForSelect(): JsonResponse
    {
        try {
            $search = request()->get('search') ?? null;
            $section = request()->get('section') ?? null;
            $factoryId = request()->get('factory_id') ?? \factoryId();

            $data = TqmDefect::query()
                ->when($search, function($query) use($search) {
                    $query->where('name', 'like', "$search%");
                })
                ->when($section, function($query) use($section) {
                    $query->where('section', $section);
                })
                ->when($factoryId, function($query) use($factoryId) {
                    $query->where('factory_id', $factoryId);
                })
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'text' => $item->name,
                        'name' => $item->name,
                    ];
                });
            $status = Response::HTTP_OK;
            $message = \SUCCESS_MSG;
        } catch (Exception $e) {
            $error = $e->getMessage();
            $message = \SOMETHING_WENT_WRONG;
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'data' => $data ?? [],
            'status' => $status,
            'message' => $message ?? null,
            'error' => $error ?? null,
        ]);
    }
}
