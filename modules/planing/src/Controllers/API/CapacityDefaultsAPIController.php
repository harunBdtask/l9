<?php

namespace SkylarkSoft\GoRMG\Planing\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CapacityDefaultsAPIController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'factory_id' => factoryId(),
            'date' => date('Y-m-d'),
            'month' => date('m'),
            'year' => date('Y'),
        ]);
    }
}
