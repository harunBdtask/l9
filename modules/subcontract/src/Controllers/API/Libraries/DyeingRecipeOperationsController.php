<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingRecipeOperation;
use Symfony\Component\HttpFoundation\Response;

class DyeingRecipeOperationsController extends Controller
{
    /**
     * @param $factoryId
     * @return JsonResponse
     */
    public function __invoke($factoryId): JsonResponse
    {
        try {
            $dyeRecipeOperations = SubDyeingRecipeOperation::query()
                ->where('factory_id', $factoryId)
                ->get(['id', 'name as text']);

            return response()->json([
                'message' => 'Fetch dye recipe operations successfully',
                'data' => $dyeRecipeOperations,
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
