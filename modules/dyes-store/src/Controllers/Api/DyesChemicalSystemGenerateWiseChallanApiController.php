<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use Symfony\Component\HttpFoundation\Response;

class DyesChemicalSystemGenerateWiseChallanApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $dyesChemicalsReceive = DyesChemicalsReceive::query()
            ->where('readonly', 0)
            ->groupBy('reference_no')
            ->get();
        return response()->json($dyesChemicalsReceive, Response::HTTP_OK);
    }
}
