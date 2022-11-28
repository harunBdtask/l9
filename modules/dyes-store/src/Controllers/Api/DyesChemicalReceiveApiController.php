<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use Symfony\Component\HttpFoundation\Response;

class DyesChemicalReceiveApiController extends Controller
{
    public function __invoke(Request $request,$challanNo): JsonResponse
    {
        $dyesChemicalReceive = DyesChemicalsReceive::query()
            ->where('readonly',0)
            ->where('reference_no',$challanNo)
            ->get();
        return response()->json($dyesChemicalReceive, Response::HTTP_OK);
    }
}
