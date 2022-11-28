<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use SkylarkSoft\GoRMG\DyesStore\Controllers\InventoryBaseController;
use SkylarkSoft\GoRMG\DyesStore\Jobs\DyesBarcodeBreak;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsIssue;
use SkylarkSoft\GoRMG\DyesStore\Requests\DyesChemicalsIssueRequest;
use Throwable;

class DyesAndChemicalIssueController extends InventoryBaseController
{
    public function index(Request $request): View
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $search = $request->get('search');
        $issues = DyesChemicalsIssue::query()->orderBy('id', 'desc');

        if ($start_date) {
            $issues->where('delivery_date', '>=', Carbon::parse($start_date));
        }
        if ($end_date) {
            $issues->where('delivery_date', '<=', Carbon::parse($end_date));
        }
        if ($search) {
            $issues->filter($search);
        }

//        dd($issues->get());

        return view('dyes-store::pages.dyes_chemicals_issue.dyes_chemicals_issue', [
            'issues' => $issues->paginate(),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'search' => $search,
            'type' => 'out',
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('dyes-store::forms.dyes_issue_form');
    }

    /**
     * @throws Throwable
     */
    public function store(DyesChemicalsIssueRequest $request, DyesChemicalsIssue $dyesChemicalsIssue): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyesChemicalsIssue->fill($request->all())->save();
            DyesBarcodeBreak::dispatchNow($request->all(), $dyesChemicalsIssue->getOriginal('details'), 'Insert');
            DB::commit();

            return response()->json($dyesChemicalsIssue, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $dyesChemicalsIssue = DyesChemicalsIssue::query()->findOrFail($id);

        return view('dyes-store::pages.dyes_chemicals_issue.dyes_chemicals_issue_view', [
            'dyesChemicalsIssue' => $dyesChemicalsIssue,
        ]);
    }

    public function edit($id): JsonResponse
    {
        try {
            $dyesChemicalIssue = DyesChemicalsIssue::query()->findOrFail($id);

            return response()->json($dyesChemicalIssue, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function update(DyesChemicalsIssueRequest $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dyesChemicalsIssue = DyesChemicalsIssue::findOrFail($id);
            $previousTransactionDetails = $dyesChemicalsIssue->details;
            $dyesChemicalsIssue->fill($request->all())->save();
            DyesBarcodeBreak::dispatchNow($request->all(), $previousTransactionDetails, 'Update');
            DB::commit();

            return response()->json($dyesChemicalsIssue, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $dyesChemicalIssue = DyesChemicalsIssue::query()->findOrFail($id);
            $dyesChemicalIssue->delete();
            $this->alert('success', 'Successfully Deleted details!');
        } catch (\Exception $e) {
            $this->alert('danger', $e->getMessage());
        }

        return Redirect::back();
    }
}
