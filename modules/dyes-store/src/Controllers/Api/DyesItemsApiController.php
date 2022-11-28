<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\DyesStore\Models\DsInvItemCategory;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use Symfony\Component\HttpFoundation\Response;

class DyesItemsApiController extends Controller
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        // $parentCategory = DsInvItemCategory::query()->where('name', 'Dyes & Chemicals')->first();
        // $categories = DsInvItemCategory::query()->where('parent_id', $parentCategory->id)->pluck('id');
        // $items = DsItem::query()->whereIn('category_id', $categories)->get();
        $items = DsItem::query()->get();

        return response()->json($items, Response::HTTP_OK);
    }
}
