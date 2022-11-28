<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;
use SkylarkSoft\GoRMG\DyesStore\Services\DyesReceiveUpdateNotificationService;
use SkylarkSoft\GoRMG\SystemSettings\Services\DyesChemicalStoreApprovalMaintainService;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalBarcode;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use SkylarkSoft\GoRMG\DyesStore\Controllers\InventoryBaseController;
use SkylarkSoft\GoRMG\DyesStore\Requests\DyesChemicalsReceiveRequest;

class DyesAndChemicalReceiveController extends InventoryBaseController
{
    public function index(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $search = $request->search;

        $receiveList = DyesChemicalsReceive::query()->with('storageLocation:id,name','supplier:id,name')->orderBy('id', 'desc');

        if ($start_date) {
            $receiveList->where('receive_date', '>=', Carbon::parse($start_date));
        }
        if ($end_date) {
            $receiveList->where('receive_date', '<=', Carbon::parse($end_date));
        }
        if ($search) {
            $receiveList->filter($search);
        }

        $approvalMaintain = DyesChemicalStoreApprovalMaintainService::getApprovalMaintainStatus();

        return view('dyes-store::pages.dyes_chemicals_receive.dyes_chemicals_receive', [
            'receive_list' => $receiveList->paginate(),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'search' => $search,
            'type' => 'in',
            'approvalMaintain' => $approvalMaintain,
        ]);
    }

    public function create()
    {
        return view('dyes-store::forms.dyes_receive_form');
    }

    public function store(DyesChemicalsReceiveRequest $request, DyesChemicalsReceive $dyesChemicalsReceive): JsonResponse
    {
        try {
            $dyesChemicalsReceive->fill($request->all())->save();

            return response()->json($dyesChemicalsReceive, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        try {
            $dyesChemicalReceive = DyesChemicalsReceive::query()->findOrFail($id);

            return view('dyes-store::pages.dyes_chemicals_receive.dyes_chemical_receive_view', [
                'dyesChemicalReceive' => $dyesChemicalReceive,
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function edit($id): JsonResponse
    {
        try {
            $dyesChemicalsReceive = DyesChemicalsReceive::query()->with('proformaInvoice:id,pi_no')->findOrFail($id);
            $approvalMaintain = DyesChemicalStoreApprovalMaintainService::getApprovalMaintainStatus();
            $dyesChemicalsReceive['approval_maintain'] = $approvalMaintain;
            $dyesChemicalsReceive['disable_status'] = $approvalMaintain == 1 && $dyesChemicalsReceive->is_approve == 1;
            $dyesChemicalsReceive['issue_status'] = DyesChemicalTransaction::query()->where('dyes_chemical_receive_id', $id)
                ->whereNotNull('dyes_chemical_issue_id')->count();

            return response()->json($dyesChemicalsReceive, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(DyesChemicalsReceiveRequest $request, $id): JsonResponse
    {
        try {
            $dyesChemicalsReceive = DyesChemicalsReceive::query()->findOrFail($id);
            $dyesChemicalsReceive->fill($request->all())->save();
            DyesReceiveUpdateNotificationService::for($dyesChemicalsReceive)->notify();

            return response()->json($dyesChemicalsReceive, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $dyesChemicalReceive = DyesChemicalsReceive::query()->findOrFail($id);
            $dyesChemicalReceive->delete();
            $this->alert('success', 'Successfully Deleted details!');
        } catch (\Exception $e) {
            $this->alert('danger', $e->getMessage());
        }

        return Redirect::back();
    }

    public function downloadBarcodes($voucherId)
    {
        $barcodes = DyesChemicalBarcode::with('voucher', 'item', 'category', 'brand')
            ->where('dyes_chemicals_receive_id', $voucherId)
            ->get();

        if (!count($barcodes)) {
            $this->alert('success', 'Barcode was not generated for this voucher!');

            return Redirect::back();
        }

        return view('dyes-store::barcode.dyes_chemicals_barcodes', [
            'dyes_barcode' => $barcodes,
        ]);
    }

    public function getReceiveNos(): JsonResponse
    {
        try {
            return response()->json(DyesChemicalsReceive::query()->pluck('system_generate_id'), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
