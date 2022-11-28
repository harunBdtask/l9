<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use SkylarkSoft\GoRMG\DyesStore\Models\DsUom;
use Symfony\Component\HttpFoundation\Response;

class ItemWiseUomApiController extends Controller
{

    public function __invoke($itemId): JsonResponse
    {
        $uom = DsItem::query()->with('uomDetails:name,id')->findOrFail($itemId)['uomDetails'] ?? null;

        return response()->json($uom, Response::HTTP_OK);
    }

}
