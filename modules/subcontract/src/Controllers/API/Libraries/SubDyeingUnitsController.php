<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubDyeingUnit;
use Symfony\Component\HttpFoundation\Response;

class SubDyeingUnitsController extends Controller
{
    /**
     * @param $factoryId
     * @return JsonResponse
     */
    public function __invoke($factoryId): JsonResponse
    {
        try {
            $subDyeingUnits = SubDyeingUnit::query()->where('factory_id', $factoryId)
                ->get(['id', 'name as text']);

            return response()->json([
                'message' => 'Sub dyeing units fetch successfully',
                'data' => $subDyeingUnits,
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
