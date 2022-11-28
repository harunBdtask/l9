<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricConstructionEntry;

class FabricConstructionApiController
{

    public function __invoke(): JsonResponse
    {
        try {
            $fabricConstructions = FabricConstructionEntry::query()
                ->get()->map(function ($collection) {
                    return [
                        'id' => $collection->construction_name,
                        'text' => $collection->construction_name,
                    ];
                });

            return response()->json([
                'data' => $fabricConstructions,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
