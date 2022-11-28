<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\HR\Models\HrShift;
use Symfony\Component\HttpFoundation\Response;

class ShiftAPIController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $shifts = HrShift::query()->get(['id', 'name as text']);
        return response()->json($shifts, Response::HTTP_OK);
    }
}
