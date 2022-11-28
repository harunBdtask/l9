<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnType;

class YarnTypeApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $yarnTypes = CompositionType::query()->get(['id', 'yarn_type as text']);
            return response()->json($yarnTypes, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
