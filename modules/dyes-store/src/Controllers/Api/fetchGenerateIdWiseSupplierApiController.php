<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use Symfony\Component\HttpFoundation\Response;

class fetchGenerateIdWiseSupplierApiController extends Controller
{

    /**
     * @param DyesChemicalsReceive $dyesChemicalsReceive
     * @return JsonResponse
     */
    public function __invoke(DyesChemicalsReceive $dyesChemicalsReceive): JsonResponse
    {
        $dyesChemicalsReceive->load([
            'supplier'
        ]);
        return response()->json($dyesChemicalsReceive, Response::HTTP_OK);
    }
}
