<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;

class FabricNatureApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $fabric_natures = FabricNature::all(['id', 'name']);

            return response()->json($fabric_natures, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }
}
