<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubcontractVariableSetting;
use Symfony\Component\HttpFoundation\Response;

class FactoryWiseVariableSettingController
{
    public function __invoke($factoryId): JsonResponse
    {
        try {
            $setting = SubcontractVariableSetting::query()
                ->where('factory_id', $factoryId)
                ->first();

            return response()->json([
                'message' => 'Fetch setting data successfully',
                'data' => $setting,
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
