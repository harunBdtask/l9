<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;
use SkylarkSoft\GoRMG\DyesStore\Repositories\DyesTransactionRepository;
use SkylarkSoft\GoRMG\DyesStore\Services\Calculations\DyesOutRateCalculator;

class DyesChemicalsItemReceiveDetailsApiController extends Controller
{
    private const IN = 'in';
    private const OUT = 'out';
    private $transactions;

    public function __construct(DyesTransactionRepository $dyesTransaction)
    {
        $this->transactions = $dyesTransaction;
    }

    public function __invoke($itemId, $deliveryDate, $storeId, $lifeEndDays): \Illuminate\Http\JsonResponse
    {
        try {
            $itemBarcode = DsItem::query()->find($itemId)->barcode;

            $itemTransactions = DyesChemicalTransaction::with('category')
                ->with('brand')
                ->with('uom')
                ->where('item_id', $itemId)
                ->where('life_end_days', $lifeEndDays)
                ->when($itemBarcode == 1, function ($query) {
                    $query->where('generate_barcodes', '=', 0);
                });
            if ($storeId != 0) {
                $itemTransactions->where('sub_store_id', $storeId);
            } else {
                $itemTransactions->whereNull('sub_store_id');
            }
            $inQty = collect($itemTransactions->get())->where('trn_type', 'in')->sum('qty');
            $outQty = collect($itemTransactions->get())->where('trn_type', 'out')->sum('qty');
            $returnQty = collect($itemTransactions->get())->where('trn_type', 'receive_return')->sum('qty');
            $actualQty = $inQty - $outQty;
            $qty = $actualQty - $returnQty;

            $itemDetails = $itemTransactions->first();

            $transactions = $this->transactions->itemTransactionsUntil($itemId, $lifeEndDays, $storeId, $deliveryDate);
            $rate = DyesOutRateCalculator::calculate($transactions);

            if ($qty === 0) {
                return response()->json("This item is not available in stock", Response::HTTP_BAD_REQUEST);
            }

            return response()->json($this->formatData($itemDetails, $rate, $qty), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function formatData($itemDetails, $rate, $qty): array
    {
        return [
            'item_id' => $itemDetails->item->id,
            'item_name' => $itemDetails->item->name,
            'category_id' => $itemDetails->category->id,
            'category_name' => $itemDetails->category->name,
            'brand_id' => $itemDetails->brand->id,
            'brand_name' => $itemDetails->brand->name,
            'uom_id' => $itemDetails->uom->id,
            'uom_name' => $itemDetails->uom->name,
            'receive_qty' => $qty,
            'delivery_qty' => '',
            'rate' => $rate,
            'life_end_days' => $itemDetails->life_end_days,
            'lot_no' => $itemDetails->lot_no,
            'batch_no' => $itemDetails->batch_no,
            'mrr_no' => $itemDetails->mrr_no,
            'sr_no' => $itemDetails->sr_no,
            'remarks' => '',
        ];
    }
}
