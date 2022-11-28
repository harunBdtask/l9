<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\SubTextileControllers\MaterialFabric;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\FabricTransferStates\FabricTransferState;
use Symfony\Component\HttpFoundation\Response;

class SearchMaterialTransferDetailsController extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $state = (new FabricTransferState())->setState($request->query('type'));

            return response()->json([
                'message' => 'Fetch Transfer Details Successfully',
                'data' => $state->handle($request),
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
