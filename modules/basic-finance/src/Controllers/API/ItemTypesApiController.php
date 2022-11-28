<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers\API;

use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\BasicFinance\Models\Procurements\ProcurementRequisition;
use Symfony\Component\HttpFoundation\Response;

class ItemTypesApiController
{

    public function __invoke(): JsonResponse
    {
        try {
            $itemTypes = collect(ProcurementRequisition::ITEM_TYPES)
                ->map(function ($collection, $key) {
                    return [
                        'id' => $key,
                        'text' => $collection,
                    ];
                })->values();
            return response()->json([
                'message' => 'Fetch departments successfully',
                'data' => $itemTypes,
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
