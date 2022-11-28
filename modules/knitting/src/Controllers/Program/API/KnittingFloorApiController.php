<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\Program\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\KnittingFloor;
use Symfony\Component\HttpFoundation\Response;

class KnittingFloorApiController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $knittingFloor = KnittingFloor::all('id', 'name as text');
            return response()->json($knittingFloor, Response::HTTP_OK);

        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
