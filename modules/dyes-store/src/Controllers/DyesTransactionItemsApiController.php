<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;
use Symfony\Component\HttpFoundation\Response;

class DyesTransactionItemsApiController extends Controller
{
    /**
     * @param null $storeId
     * @return JsonResponse
     */
    public function __invoke($storeId): JsonResponse
    {
        try {
            $transactions = DyesChemicalTransaction::query();
            if ($storeId == 0) {
                $transactions->whereNull('sub_store_id');
            } else {
                $transactions->where('sub_store_id', $storeId);
            }
            $transactionsItemsId = collect($transactions->get())->pluck('item_id')->toArray();
            $items = DsItem::query()->whereIn('id', $transactionsItemsId)->get(['id', 'name']);
            return response()->json($items, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
