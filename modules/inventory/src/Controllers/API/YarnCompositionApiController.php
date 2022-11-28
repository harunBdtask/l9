<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use Symfony\Component\HttpFoundation\Response;

class YarnCompositionApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $yarnCompositions = YarnComposition::query()->get(['id', 'yarn_composition as text']);
            return response()->json($yarnCompositions, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
