<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreMrr;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Inventory\Services\UID\TrimsStoreMrrService;
use Symfony\Component\HttpFoundation\Response;

class TrimsMrrNoApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $trimsMrrNo = TrimsStoreMrrService::generateUniqueId();

            return response()->json([
                'data' => $trimsMrrNo ?? [],
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
