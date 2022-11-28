<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use Symfony\Component\HttpFoundation\Response;

class FactoryWiseLocationApiController extends Controller
{
    public function __invoke(Factory $factory): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Factory location fetch successfully',
                'data' => $factory->factory_address ?? null,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
