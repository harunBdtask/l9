<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;
use Symfony\Component\HttpFoundation\Response;

class SubTextileOrderApiController extends Controller
{
    /**
     * @param Request $request
     * @param $factoryId
     * @return JsonResponse
     */
    public function __invoke(Request $request, $factoryId, $partyId = false): JsonResponse
    {
        try {
            $orders = SubTextileOrder::query()
                ->where('factory_id', $factoryId)
                ->when($partyId, function ($q) use ($partyId) {
                    return $q->where('supplier_id', $partyId);
                })
                ->get(['id', 'order_no as text']);

            return response()->json($orders, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
