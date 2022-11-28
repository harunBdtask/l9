<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Controllers;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\GeneralStore\Models\GsInvBarcode;
use SkylarkSoft\GoRMG\GeneralStore\Repositories\TransactionRepository;
use SkylarkSoft\GoRMG\GeneralStore\Services\Calculations\OutRateCalculator;

class BarcodeController
{
    /**
     * @var TransactionRepository
     */
    private $transactions;

    public function __construct(TransactionRepository $transactions)
    {
        $this->transactions = $transactions;
    }

    public function scan(Request $request): \Illuminate\Http\JsonResponse
    {
        $code = $request->get('barcode');
        $deliveryDate = $request->get('deliveryDate');

        if (!$deliveryDate) {
            return $this->jsonError('Provide Delivery Date for out rate!');
        }

        $barcode = GsInvBarcode::with('item', 'brand')->where('code', $code)->first();

        if (!$barcode) {
            return $this->jsonError('Entry is not found for this code!');
        }

        if ($barcode->scanned()) {
            return $this->jsonError('Barcode is already scanned!');
        }

        $transactions = $this->transactions->itemTransactionsUntil($barcode->item_id, $deliveryDate);

        $rate = (new OutRateCalculator)->calculate($transactions) ?? null;

        $data = $this->formatBarcodeData($barcode, $rate);

        return $this->jsonResponse($data);
    }

    private function jsonResponse($data, $code = 200)
    {
        return response()->json($data, $code);
    }

    private function jsonError($message)
    {
        return response()->json([
            'error' => true,
            'msg' => $message
        ]);
    }

    /**
     * @param $barcode
     * @param $rate
     * @return array
     */
    private function formatBarcodeData($barcode, $rate): array
    {
        return [
            'id' => $barcode->id,
            'item_id' => $barcode->item_id,
            'code' => $barcode->code,
            'item' => $barcode->item->name ?? null,
            'brand_id' => $barcode->brand_id,
            'brand' => $barcode->brand->name ?? null,
            'qty' => $barcode->qty,
            'delivery_qty' => $barcode->qty,
            'rate' => $rate,
            'remarks' => ''
        ];
    }

}
