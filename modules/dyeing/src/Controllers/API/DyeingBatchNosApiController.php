<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;

class DyeingBatchNosApiController extends Controller
{

    /**
     * @param $buyerId
     * @return JsonResponse
     */
    public function __invoke($buyerId): JsonResponse
    {
        try {
            $dyeingBatchNos = DyeingBatch::query()
                ->where('buyer_id', $buyerId)
                ->get(['id', 'batch_no as text']);

            return response()->json([
                'message' => 'Fetch batch no successfully',
                'data' => $dyeingBatchNos,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
