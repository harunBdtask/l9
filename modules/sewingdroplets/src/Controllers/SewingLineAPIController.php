<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class SewingLineAPIController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $lines = Line::query()->withoutGlobalScope('factoryId')->get(['id', 'line_no as text']);
        return response()->json($lines);
    }
}
