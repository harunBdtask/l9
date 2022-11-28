<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceive;
use Symfony\Component\HttpFoundation\Response;

class TrimsStoreReceiveChallanNosApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $factoryId = $request->query('factory_id');
        $storeId = $request->query('store_id');

        $receiveChallanNos = TrimsStoreReceive::query()
            ->where('factory_id', $factoryId)
            ->where('store_id', $storeId)
            ->orderByDesc('id')
            ->get(['id', 'challan_no as text']);

        return response()->json([
            'message' => 'Fetch trims store receive challan nos successfully',
            'data' => $receiveChallanNos,
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);
    }
}
