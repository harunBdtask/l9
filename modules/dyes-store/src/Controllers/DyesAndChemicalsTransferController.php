<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use SkylarkSoft\GoRMG\DyesStore\Controllers\InventoryBaseController;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;
use SkylarkSoft\GoRMG\DyesStore\Requests\DyesChemicalsTransferRequest;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DyesAndChemicalsTransferController extends InventoryBaseController
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $transferList = DyesChemicalTransaction::query()
            ->with('item', 'uom', 'fromStore', 'toStore')
            ->whereNotNull('ref')
            ->where('trn_type', 'out')
            ->orderBy('id', 'desc')
            ->paginate();

        return view('dyes-store::pages.dyes_chemicals_transactions.dyes_chemicals_transactions', [
            'transfer_list' => $transferList,
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('dyes-store::forms.dyes_transfer_form');
    }

    /**
     * @throws Throwable
     */
    public function store(DyesChemicalsTransferRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $outTransaction = $this->outTransaction($request, new DyesChemicalTransaction());
            $this->inTransaction($request, new DyesChemicalTransaction(), $outTransaction);
            DB::commit();

            return response()->json('Data insert successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function edit($id): JsonResponse
    {
        try {
            $dyesChemicalTransaction = DyesChemicalTransaction::query()->findOrFail($id);

            return response()->json($dyesChemicalTransaction, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function update(DyesChemicalsTransferRequest $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $outTransaction = $this->outTransaction($request, DyesChemicalTransaction::query()->findOrFail($id));
            $dyesChemicalInTransaction = DyesChemicalTransaction::query()->where('receive_id', $id)->first();
            $this->inTransaction($request, $dyesChemicalInTransaction, $outTransaction);
            DB::commit();

            return response()->json('Data update successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            DyesChemicalTransaction::query()->where('id', $id)->delete();
            DyesChemicalTransaction::query()->where('receive_id', $id)->delete();
            DB::commit();
            $this->alert('success', 'Successfully Deleted details!');
        } catch (\Exception $e) {
            $this->alert('danger', $e->getMessage());
        }

        return Redirect::back();
    }

    /**
     * @param $request
     * @param $dyesChemicalTransaction
     */
    public function outTransaction($request, $dyesChemicalTransaction)
    {
        $dyesChemicalTransaction->item_id = $request->get('item_id');
        $dyesChemicalTransaction->category_id = $request->get('category_id');
        $dyesChemicalTransaction->brand_id = $request->get('brand_id');
        $dyesChemicalTransaction->qty = $request->get('trn_qty');
        $dyesChemicalTransaction->rate = $request->get('rate');
        $dyesChemicalTransaction->trn_date = $request->get('trn_date');
        $dyesChemicalTransaction->trn_type = 'out';
        $dyesChemicalTransaction->ref = $request->get('from_store') === 0 ? 'Transfer to sub store' : 'Transfer to main store';
        $dyesChemicalTransaction->sub_store_id = $request->get('from_store') === 0 ? null : $request->get('from_store');
        $dyesChemicalTransaction->trn_store = $request->get('to_store') === 0 ? null : $request->get('to_store');
        $dyesChemicalTransaction->uom_id = $request->get('uom_id');
        $dyesChemicalTransaction->sr_no = $request->get('sr_no');
        $dyesChemicalTransaction->lot_no = $request->get('lot_no');
        $dyesChemicalTransaction->mrr_no = $request->get('mrr_no');
        $dyesChemicalTransaction->batch_no = $request->get('batch_no');
        $dyesChemicalTransaction->life_end_days = $request->get('life_end_days');
        $dyesChemicalTransaction->save();

        return $dyesChemicalTransaction;
    }

    /**
     * @param $request
     * @param $dyesChemicalTransaction
     * @param $outTransaction
     */
    public function inTransaction($request, $dyesChemicalTransaction, $outTransaction)
    {
        $dyesChemicalTransaction->item_id = $request->get('item_id');
        $dyesChemicalTransaction->category_id = $request->get('category_id');
        $dyesChemicalTransaction->brand_id = $request->get('brand_id');
        $dyesChemicalTransaction->qty = $request->get('trn_qty');
        $dyesChemicalTransaction->rate = $request->get('rate');
        $dyesChemicalTransaction->trn_date = $request->get('trn_date');
        $dyesChemicalTransaction->trn_type = 'in';
        $dyesChemicalTransaction->ref = $request->get('from_store') === 0 ? 'Receive from main store' : 'Receive from sub store';
        $dyesChemicalTransaction->sub_store_id = $request->get('from_store') === 0 ? $request->get('to_store') : null;
        $dyesChemicalTransaction->trn_store = $request->get('to_store') === 0 ? $request->get('from_store') : null;
        $dyesChemicalTransaction->receive_id = $outTransaction->id;
        $dyesChemicalTransaction->uom_id = $request->get('uom_id');
        $dyesChemicalTransaction->sr_no = $request->get('sr_no');
        $dyesChemicalTransaction->lot_no = $request->get('lot_no');
        $dyesChemicalTransaction->mrr_no = $request->get('mrr_no');
        $dyesChemicalTransaction->batch_no = $request->get('batch_no');
        $dyesChemicalTransaction->life_end_days = $request->get('life_end_days');
        $dyesChemicalTransaction->save();
    }
}
