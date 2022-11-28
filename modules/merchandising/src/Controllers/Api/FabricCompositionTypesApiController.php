<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use Symfony\Component\HttpFoundation\Response;

class FabricCompositionTypesApiController
{

    public function __invoke(): JsonResponse
    {
        try {
            $compositionTypes = CompositionType::query()->get(['id', 'name as text']);

            return response()->json([
                'data' => $compositionTypes,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_OK);
        }
    }

}
