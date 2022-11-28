<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricStoreVariableSetting;

class FabricStoreVariableSettingApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $fabricStoreVariableSetting = FabricStoreVariableSetting::query()->first();

            return response()->json($fabricStoreVariableSetting, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
