<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\DyeingProcess;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\BatchDetailsStates\BatchDetailsState;
use Symfony\Component\HttpFoundation\Response;

class OrderDetailsFilterController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $state = (new BatchDetailsState())->setState($request);
            $subGreyStoreIssueDetails = $state->format($request);

            return response()->json($subGreyStoreIssueDetails, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
