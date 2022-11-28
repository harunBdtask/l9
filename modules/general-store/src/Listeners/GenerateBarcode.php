<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Listeners;

use SkylarkSoft\GoRMG\GeneralStore\Events\TransactionCompleted;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvBarcode;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsItem;

class GenerateBarcode
{
    public function handle(TransactionCompleted $transaction)
    {
        foreach ($transaction->getItems() as $item) {

            $invItem = GsItem::find($item->item_id);

            if (!$invItem->barcode) {
                continue;
            }

            $chunk_quantity = isset($item->delivery_qty) ? $item->delivery_qty : $item->qty;
            $chunks = $this->chunkQty($chunk_quantity, $invItem->qty);
            foreach ($chunks as $qty) {

                $barcode = new GsInvBarcode([
                    'item_id' => $item->item_id,
                    'brand_id' => isset($item->brand_id) ? $item->brand_id : null,
                    'voucher_id' => $transaction->getVoucherId(),
                    'qty' => $qty,
                    'code' => '',
                    'type' => $transaction->getType()
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
