<?php

namespace SkylarkSoft\GoRMG\Commercial\Controllers\Imports;

use App\Constants\ApplicationConstant;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Commercial\Constants\CommercialConstant;
use SkylarkSoft\GoRMG\Commercial\Models\B2BMarginLC;
use SkylarkSoft\GoRMG\Commercial\Models\Imports\ImportLcCharge;
use Symfony\Component\HttpFoundation\Response;

class ImportLCChargeController
{
    public function letterOfCreditsSearch(): JsonResponse
    {
        $request = request()->all();
        $factory_id = $request->factory_id ?? null;
        $lien_bank_id = $request->lien_bank_id ?? null;
        $supplier_id = $request->supplier_id ?? null;
        $lc_date_from = $request->lc_date_from ?? null;
        $lc_date_to = $request->lc_date_to ?? null;
        $lc_no = $request->lc_no ?? null;

        $b2b_margin_lcs = B2BMarginLC::query()
            ->when($factory_id && ! $lc_no && ! $lien_bank_id, function ($query) use ($factory_id) {
                $query->where('factory_id', $factory_id);
            })
            ->when($lien_bank_id && ! $lc_no, function ($query) use ($lien_bank_id) {
                $query->where('lien_bank_id', $lien_bank_id);
            })
            ->when($supplier_id && ! $lc_no, function ($query) use ($supplier_id) {
                $query->where('supplier_id', $supplier_id);
            })
            ->when($lc_date_from && $lc_date_to && ! $lc_no, function ($query) use ($lc_date_from, $lc_date_to) {
                $query->whereDate('lc_date', '>=', $lc_date_from)
                    ->whereDate('lc_date', '<=', $lc_date_to);
            })
            ->when($lc_no, function ($query) use ($lc_no) {
                $query->where('lc_number', $lc_no);
            })
            ->get();
        $data = [];
        $sl = 0;
        foreach ($b2b_margin_lcs as $b2b_margin_lc) {
            $lc_type_id = $b2b_margin_lc->lc_type ?? null;
            $lc_type = $lc_type_id ? (array_key_exists($lc_type_id, CommercialConstant::LC_TYPES) ? CommercialConstant::LC_TYPES[$lc_type_id] : null) : null;
            $charge_payment = $b2b_margin_lc->importLcCharge->count() ? $b2b_margin_lc->importLcCharge->sum('amount') : 0;
            $data[] = [
                'sl' => ++$sl,
                'b_to_b_margin_lc_id' => $b2b_margin_lc->id ?? null,
                'lc_no' => $b2b_margin_lc->lc_number ?? null,
                'factory_id' => $b2b_margin_lc->factory_id,
                'supplier_id' => $b2b_margin_lc->supplier_id ?? null,
                'supplier' => $b2b_margin_lc->supplier->name ?? null,
                'lien_bank_id' => $b2b_margin_lc->lien_bank_id ?? null,
                'bank' => $b2b_margin_lc->lienBank->name ?? null,
                'item_id' => $b2b_margin_lc->item_id ?? null,
                'item_category' => $b2b_margin_lc->item->item_name ?? null,
                'lc_type_id' => $lc_type_id,
                'lc_type' => $lc_type,
                'lc_value' => $b2b_margin_lc->lc_value ?? null,
                'charge_payment' => $charge_payment,
            ];
        }

        return response()->json($data);
    }

    public function index($id): JsonResponse
    {
        $charges = ImportLcCharge::query()->where('b_to_b_margin_lc_id', $id)
            ->latest()->get()
            ->map(function ($data) {
                return [
                    'id' => $data->id,
                    'pay_head_id' => $data->pay_head_id,
                    'pay_head' => $data->pay_head_id ?
                        (array_key_exists($data->pay_head_id, CommercialConstant::PAY_HEADS) ?
                            CommercialConstant::PAY_HEADS[$data->pay_head_id] : null) : null,
                    'charge_for_id' => $data->charge_for_id,
                    'charge_for' => $data->charge_for_id ?
                        (array_key_exists($data->charge_for_id, CommercialConstant::CHARGES_FOR) ?
                            CommercialConstant::CHARGES_FOR[$data->charge_for_id] : null) : null,
                    'amount' => $data->amount,
                    'pay_date' => $data->pay_date,
                    'b_to_b_margin_lc_id' => $data->b_to_b_margin_lc_id,
                    'factory_id' => $data->factory_id,
                ];
            });

        return response()->json($charges);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            \DB::beginTransaction();
            if ($request->id) {
                $charge = ImportLcCharge::query()->findOrFail($request->id);
            } else {
                $charge = new ImportLcCharge();
            }
            $charge->fill($request->all());
            $charge->save();
            \DB::commit();

            return response()->json(['message' => ApplicationConstant::S_STORED]);
        } catch (\Throwable $e) {
            return response()->json(
                ['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function delete(ImportLcCharge $importLcCharge): JsonResponse
    {
        try {
            DB::beginTransaction();
            $importLcCharge->delete();
            DB::commit();

            return response()->json(['message' => ApplicationConstant::S_DELETED]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(
                ['message' => ApplicationConstant::SOMETHING_WENT_WRONG, 'errMsg' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
