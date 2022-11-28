<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;

class FactoryWiseSewingFloorAPIController extends Controller
{
    /**
     * @param $factoryId
     * @return JsonResponse
     */
    public function __invoke($factoryId): JsonResponse
    {
        $floors = Floor::query()
            ->withoutGlobalScope('factoryId')
            ->where('factory_id', $factoryId)
            ->get(['id', 'floor_no as text']);
        return response()->json($floors);
    }
}
