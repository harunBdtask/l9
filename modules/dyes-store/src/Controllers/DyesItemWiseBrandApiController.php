<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DsBrand;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use Symfony\Component\HttpFoundation\Response;

class DyesItemWiseBrandApiController extends Controller
{
    /**
     * @param $itemId
     * @return JsonResponse
     */
    public function __invoke($itemId): JsonResponse
    {
        $item = DsItem::query()->where('id', $itemId)->first();
        $brand = DsBrand::query()->where('id', $item->brand_id)->first();

        return response()->json($brand, Response::HTTP_OK);
    }
}
