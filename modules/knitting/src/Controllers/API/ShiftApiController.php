<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;

class ShiftApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $shifts = Shift::query()
                ->get()
                ->map(function ($collection) {
                    return [
                        'id' => $collection->id,
                        'text' => $collection->shift_name,
                        'start_time' => $collection->start_time,
                        'end_time' => $collection->end_time,
                        'extra_time' => $collection->extra_time,
                    ];
                });
            return response()->json($shifts, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
