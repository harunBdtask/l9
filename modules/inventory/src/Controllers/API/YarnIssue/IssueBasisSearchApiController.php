<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnIssue;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IssueBasisSearchApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $receiveBasis = [];
            return response()->json($receiveBasis, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
