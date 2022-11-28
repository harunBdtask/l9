<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Shift;
use Symfony\Component\HttpFoundation\Response;

class ShiftsApiController extends Controller
{
    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $shifts = Shift::query()->get(['shift_name as text', 'id']);

            return response()->json([
                "data" => $shifts,
                "message" => "Shifts fetched successfully",
                "status" => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
