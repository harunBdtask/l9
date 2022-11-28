<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DsInvItemCategory;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use Symfony\Component\HttpFoundation\Response;

class DyesItemWiseCategoryApiController extends Controller
{
    /**
     * @param $itemId
     * @return JsonResponse
     */
    public function __invoke($itemId): JsonResponse
    {
        $item = DsItem::query()->where('id', $itemId)->first();
        $category = DsInvItemCategory::query()->where('id', $item->category_id)->first();

        return response()->json($category, Response::HTTP_OK);
    }
}
