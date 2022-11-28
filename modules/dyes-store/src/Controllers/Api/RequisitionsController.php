<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipeRequisition;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\DyesStore\Services\States\RequisitionStates\RequisitionState;
use Illuminate\Http\Request;

class RequisitionsController extends Controller
{

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $requisitionsState = RequisitionState::setState($request->get('type'));
            $requisitions = $requisitionsState->handle();

            return response()->json([
                'message' => 'Fetch recipe requisitions successfully',
                'data' => $requisitions,
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
