<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceive;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\UID\TrimsStoreReceiveService;
use Symfony\Component\HttpFoundation\Response;

class TrimsStoreReceiveUniqueIdController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        try {
            $uniqueId = TrimsStoreReceiveService::generateUniqueId();

            return response()->json([
                'message' => 'Fetch trims store receive unique_id successfully',
                'data' => $uniqueId,
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
