<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnReceive;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Inventory\Models\Store;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;
use Symfony\Component\HttpFoundation\Response;

class YarnTypeApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            $stores = CompositionType::query()
                ->orderByDesc('id')
                ->get(['id', 'name as text']);
            return response()->json($stores, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
