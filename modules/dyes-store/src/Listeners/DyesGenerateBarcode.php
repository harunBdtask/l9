<?php

namespace SkylarkSoft\GoRMG\DyesStore\Listeners;

use SkylarkSoft\GoRMG\DyesStore\Events\DyesTransactionCompleted;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalBarcode;

class DyesGenerateBarcode
{
    public function handle(DyesTransactionCompleted $transaction)
    {
        $voucher = $transaction->getVoucher();
        $items = $transaction->getItems();

        foreach ($items as $item) {

            $invItem = DsItem::find($item->item_id);

            if (!$invItem->barcode) {
                continue;
            }

            $chunk_quantity = $item->receive_qty ?? $item->qty;
            $chunks = $this->chunkQty($chunk_quantity, $invItem->qty);

            foreach ($chunks as $qty) {
                $barcode = new DyesChemicalBarcode([
                    'receive_date' => $voucher->receive_date,
                    'item_id' => $item->item_id,
                    'category_id' => $item->category_id,
                    'brand_id' => $item->brand_id ?? null,
                    'uom_id' => $item->uom_id ?? null,
                    'dyes_chemicals_receive_id' => $voucher->id,
                    'life_end_days' => $item->details->life_end_days,
                    'lot_no' => $item->details->lot_no,
                    'batch_no' => $item->details->batch_no,
                    'mrr_no' => $item->details->mrr_no,
                    'sr_no' => $item->details->sr_no,
                    'qty' => $qty,
                    'delivery_qty' => 0,
                    'status' => 1,
                ]);

                $barcode->save();
                $barcode->code = $invItem->prefix . sprintf('%05d', $barcode->id);
                $barcode->save();
            }
        }
    }

    /*
    *  e.g - chunkQty(10, 3) ==> [3, 3, 3, 1]
    */
    private function chunkQty($qty, $divisor): array
    {
        $quotient = intdiv($qty, $divisor);
        $chunk = [];

        $remainder = $qty % $divisor;

        while ($quotient--) {
            array_push($chunk, $divisor);
        }

        if ($remainder) {
            array_push($chunk, $remainder);
        }

        return $chunk;
    }
}
