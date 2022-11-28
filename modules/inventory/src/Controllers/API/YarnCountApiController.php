<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use Symfony\Component\HttpFoundation\Response;

class YarnCountApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $yarnCounts = YarnCount::query()->get(['id', 'yarn_count as text']);
            return response()->json($yarnCounts, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
