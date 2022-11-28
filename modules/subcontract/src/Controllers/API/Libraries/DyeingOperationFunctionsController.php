<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingOperationFunction;
use Symfony\Component\HttpFoundation\Response;

class DyeingOperationFunctionsController extends Controller
{
    /**
     * @param $dyeRecipeOperationId
     * @return JsonResponse
     */
    public function __invoke($dyeRecipeOperationId): JsonResponse
    {
        try {
            $dyeOperationFunctions = SubDyeingOperationFunction::query()
                ->where('dyeing_recipe_operation_id', $dyeRecipeOperationId)
                ->get(['id', 'function_name as text']);

            return response()->json([
                'message' => 'Fetch dye operation function successfully',
                'data' => $dyeOperationFunctions,
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
