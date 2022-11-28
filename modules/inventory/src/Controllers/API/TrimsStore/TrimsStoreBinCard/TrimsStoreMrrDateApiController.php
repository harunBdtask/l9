<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreBinCard;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrr;
use Symfony\Component\HttpFoundation\Response;

class TrimsStoreMrrDateApiController extends Controller
{
    /**
     * @param TrimsStoreMrr $mrr
     * @return JsonResponse
     */
    public function __invoke(TrimsStoreMrr $mrr): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Fetch Trims Bin Card Successfully',
                'data' => $mrr ?? null,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
