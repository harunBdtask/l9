<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\SubTextileAPIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use Symfony\Component\HttpFoundation\Response;

class FabricCompositionApiController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $compositions = NewFabricComposition::query()->get()
            ->map(function ($fabric_composition) {
                $composition = '';
                $first_key = $fabric_composition->newFabricCompositionDetails->keys()->first();
                $last_key = $fabric_composition->newFabricCompositionDetails->keys()->last();
                $fabric_composition->newFabricCompositionDetails->each(
                    function ($fabric_item, $key) use (&$composition, $first_key, $last_key, $fabric_composition) {
                        $composition .= ($key === $first_key) ? "[" : '';
                        $composition .= "{$fabric_item->yarnComposition->yarn_composition} {$fabric_item->percentage}%";
                        $composition .= ($key !== $last_key) ? ', ' : ']';
                    }
                );

                return [
                    "id" => $fabric_composition->id,
                    "text" => $composition,
                    "construction" => $fabric_composition->construction,
                    "gsm" => $fabric_composition->gsm,
                    "dia" => $fabric_composition->finish_fabric_dia,
                ];
            });

        return response()->json($compositions, Response::HTTP_OK);
    }
}
