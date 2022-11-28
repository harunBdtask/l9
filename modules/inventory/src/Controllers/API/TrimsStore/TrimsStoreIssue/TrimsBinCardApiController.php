<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreIssue;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCard;
use Symfony\Component\HttpFoundation\Response;

class TrimsBinCardApiController extends Controller
{
    public function __invoke(TrimsStoreBinCard $binCard): JsonResponse
    {
        try {
            return response()->json([
                'data' => $binCard ?? [],
                'status' => Response::HTTP_OK,
                'message' => \SUCCESS_MSG,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => \SOMETHING_WENT_WRONG,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
