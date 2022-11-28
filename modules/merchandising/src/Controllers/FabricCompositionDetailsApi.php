<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;

class FabricCompositionDetailsApi extends Controller
{
    /**
     * @param $fabric_composition_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($fabric_composition_id): \Illuminate\Http\JsonResponse
    {
        try {
            $fabric_composition = NewFabricComposition::with(
                [
                    'newFabricCompositionDetails.yarnComposition',
                    'newFabricCompositionDetails.yarnCount',
                    'newFabricCompositionDetails.compositionType',
                ]
            )
                ->where("id", $fabric_composition_id)
                ->first();

            return response()->json($fabric_composition, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
