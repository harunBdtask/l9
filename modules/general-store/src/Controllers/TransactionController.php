<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use SkylarkSoft\GoRMG\GeneralStore\Events\TransactionCompleted;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvVoucher;
use SkylarkSoft\GoRMG\GeneralStore\Services\Calculations\AvailableQtyCalculator;
use SkylarkSoft\GoRMG\GeneralStore\Services\Calculations\OutRateCalculator;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvTransaction;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class TransactionController extends InventoryBaseController
{
    public function makeTransaction($store, GsInvVoucher $voucher): \Illuminate\Http\RedirectResponse
    {
        $transactions = [];

        foreach ($voucher->details as $detail) {
            try {
                $transactions[] = $this->formatTransaction($voucher, $detail);
            } catch (Exception $e) {
                $this->alert('danger', $e->getMessage());
                return Redirect::route('vouchers', ['storeId' => $store]);
            }
        }

        try {
            DB::beginTransaction();
            GsInvTransaction::insert($transactions);
            $voucher->makeReadonly();
            if ($voucher->type == "in") {
                event(new TransactionCompleted($voucher));
            }
            DB::commit();
            $this->alert('success', 'Transaction Successful!');
        } catch (Exception $e) {
            DB::rollBack();
            $this->alert('danger', 'Transaction Failed!' . $e->getMessage());
        } finally {
            return Redirect::route('vouchers', ['storeId' => $store]);
        }
    }

    /*
     * @ajax Return Out rate for an item
     */
    public function getOutRate(OutRateCalculator $outRateCalculator): \Illuminate\Http\JsonResponse
    {
        $deliveryDate = request('deliveryDate');
        $itemId = request('itemId');
        $firstDateOfYear = Carbon::today()
            ->firstOfYear()
            ->toDateString();

        $transactions = GsInvTransaction::where('item_id', $itemId)
            ->select(DB::raw('*, qty * rate as total'))
            ->whereBetween('trn_date', [$firstDateOfYear, $deliveryDate])
            ->get();
        $rate = $outRateCalculator->calculate($transactions) ?: null;

        return $this->jsonResponse([
            'rate' => $rate
        ]);
    }

    public function getItemQty(Request $request)
    {
        $items_transaction = GsInvTransaction::where([
            "item_id" => $request->item_id,
            "brand_id" => $request->brand_id,
        ])->get();
        $in_qty = collect($items_transaction)->where("trn_type", "=", "in")->sum("qty");
        $out_qty = collect($items_transaction)->where("trn_type", "=", "out")->sum("qty");
        return $in_qty - $out_qty;
    }

    private function formatTransaction(GsInvVoucher $voucher, $detail): array
    {
        if ($voucher->type == 'in') {
            $model = Supplier::class;
        } elseif ($voucher->type == 'out') {
            $model = User::class;

            $transactions = GsInvTransaction::where('item_id', $detail->item_id)
                ->get(['trn_type', 'qty']);

            $availableQty = (new AvailableQtyCalculator)->calculate($transactions);

            if (($availableQty - $detail->qty) < 0) {
                $item = GsItem::find($detail->item_id);
                $message = "{$item->name} is not available in store. ";
                $message .= $detail->qty - $availableQty;
                $message .= " {$item->uom} more is needed.";

                throw new \Exception($message);
            }
        }

        return [
            'item_id' => $detail->item_id,
            'brand_id' => isset($detail->brand_id) ? $detail->brand_id : null,
            'qty' => isset($detail->delivery_qty) ? $detail->delivery_qty : $detail->qty,
            'rate' => $detail->rate,
            'trn_date' => $voucher->trn_date,
            'trn_type' => $voucher->type,
            'trn_with' => $voucher->trn_with,
            'store' => $voucher->store,
            'voucher_id' => $voucher->id,
            'model' => $model,
            'created_by' => auth()->user()->id ?? null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
