<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Inventory\Services\States\StoreStates\StoreStates;
use Symfony\Component\HttpFoundation\Response;

class FabricReceiveDefaultApiController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        try {
            $state = StoreStates::setState(1);
            $defaults['firstStoreId'] = $state->handle()->first()['id'] ?? null;

            return response()->json($defaults, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
