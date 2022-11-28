<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use Symfony\Component\HttpFoundation\Response;

class YarnStoreApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $itemId = Item::query()
                ->where('item_name', 'LIKE', '%Yarn%')
                ->first()->id;

            $factoryId = request('factory_id') ?? factoryId();
            $stores = Store::query()
                ->where('factory_id', $factoryId)
                ->where('item_category_id', $itemId)
                ->orderByDesc('id')
                ->get(['id', 'name as text']);
            return response()->json($stores, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
