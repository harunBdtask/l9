<?php

namespace SkylarkSoft\GoRMG\TQM\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\TQM\Models\TqmDefect;
use Symfony\Component\HttpFoundation\Response;

class CommonApiController extends Controller
{
    public function cuttingFloors(): JsonResponse
    {
        try {
            $cuttingFloors = CuttingFloor::query()->get(['id', 'floor_no as text']);
            return response()->json($cuttingFloors, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json('Something Went Wrong!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchCuttingDefects(): JsonResponse
    {
        try {
            $defects = TqmDefect::query()
                ->where('section', TqmDefect::CUTTING_SECTION)
                ->get(['id', 'name as text']);
            return response()->json($defects, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json('Something Went Wrong!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sewingFloors(): JsonResponse
    {
        try {
            $sewingFloors = Floor::query()->get(['id', 'floor_no as text']);
            return response()->json($sewingFloors, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json('Something Went Wrong!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function finishingFloors(): JsonResponse
    {
        try {
            $sewingFloors = FinishingFloor::query()->get(['id', 'name as text']);
            return response()->json($sewingFloors, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json('Something Went Wrong!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
