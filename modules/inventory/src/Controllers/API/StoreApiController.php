<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\Inventory\Services\States\StoreStates\StoreStates;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use Symfony\Component\HttpFoundation\Response;

class StoreApiController extends Controller
{

    // const ITEM_CATEGORY_ID = 2;

    public function __invoke(Request $request): JsonResponse
    {
        try {
//            $itemCategoryId = Item::query()->where('item_name', 'Knit Finish Fabrics')
//                ->first()['id'];
//
//            $stores = Store::query()->where('item_category_id', $itemCategoryId)
//                ->get(['id', 'name as text']);

            $state = StoreStates::setState($request->input('type'));

            $stores = $state->handle();

            return response()->json($stores, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
