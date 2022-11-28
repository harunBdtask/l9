<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\CareLabelType;
use Symfony\Component\HttpFoundation\Response;

class CareLabelTypesApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $careLabelTypes = CareLabelType::query()->get(['id', 'name as text']);

            return response()->json([
                'message' => 'Fetch care label types',
                'data' => $careLabelTypes,
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
