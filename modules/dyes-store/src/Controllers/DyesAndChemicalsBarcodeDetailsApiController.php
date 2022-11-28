<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\DyesStore\Controllers\InventoryBaseController;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalBarcode;
use SkylarkSoft\GoRMG\DyesStore\Repositories\DyesTransactionRepository;
use SkylarkSoft\GoRMG\DyesStore\Services\Calculations\DyesOutRateCalculator;

class DyesAndChemicalsBarcodeDetailsApiController extends InventoryBaseController
{
    /**
     * @var DyesTransactionRepository
     */
    private $transactions;

    public function __construct(DyesTransactionRepository $dyesTransaction)
    {
        $this->transactions = $dyesTransaction;
    }

    public function scan(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $barcode = $request->get('barcode');
            $deliveryDate = $request->get('delivery_date');
            $storeId = $request->get('store_id');

            if (!$deliveryDate) {
                return $this->jsonResponse('Provide Delivery Date for out rate!', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $barcodeDetails = DyesChemicalBarcode::with('voucher')
                ->with('item:id,name')
                ->with('category:id,name')
                ->with('brand:id,name')
                ->where('code', $barcode)
                ->when($storeId == 0, function ($query) {
                    $query->whereNull('store_id');
                })
                ->when($storeId != 0, function ($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                })
                ->first();

            if (!$barcodeDetails) {
                return $this->jsonResponse('Entry is not found this code', Response::HTTP_NOT_FOUND);
            }

            if ($barcodeDetails->scanned()) {
                return $this->jsonResponse('Barcode is already scanned', Response::HTTP_NOT_FOUND);
            }

            $transactions = $this->transactions->itemTransactionsUntil($barcodeDetails->item_id, $barcodeDetails->life_end_days, $storeId, $deliveryDate);
            $rate = DyesOutRateCalculator::calculate($transactions);

            return $this->jsonResponse($this->formatData($barcodeDetails, $rate), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->jsonResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function formatData($barcodeDetails, $rate): array
    {
        return [
            'barcode_id' => $barcodeDetails->id,
            'item_id' => $barcodeDetails->item->id,
            'item_name' => $barcodeDetails->item->name,
            'category_id' => $barcodeDetails->category->id,
            'category_name' => $barcodeDetails->category->name,
            'brand_id' => $barcodeDetails->brand->id,
            'brand_name' => $barcodeDetails->brand->name,
            'uom_id' => $barcodeDetails->uom->id,
            'uom_name' => $barcodeDetails->uom->name,
            'receive_qty' => $barcodeDetails->qty,
            'delivery_qty' => $barcodeDetails->qty,
            'rate' => $rate,
            'life_end_days' => $barcodeDetails->life_end_days,
            'lot_no' => $barcodeDetails->lot_no,
            'batch_no' => $barcodeDetails->batch_no,
            'mrr_no' => $barcodeDetails->mrr_no,
            'sr_no' => $barcodeDetails->sr_no,
            'remarks' => '',
        ];
    }
}
