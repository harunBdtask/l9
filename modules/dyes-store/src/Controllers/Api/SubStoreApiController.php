<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DsStoreModel;
use Symfony\Component\HttpFoundation\Response;

class SubStoreApiController extends Controller
{
    public function __invoke($storeId): JsonResponse
    {
        try {
//            $subStores = DsStoreModel::query()->where('parent', $storeId)->get(['id', 'name', 'parent']);
            $subStores = DsStoreModel::query()->get(['id', 'name', 'parent']);

            return response()->json($subStores, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
