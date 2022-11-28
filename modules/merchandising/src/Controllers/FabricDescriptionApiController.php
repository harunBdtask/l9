<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use Symfony\Component\HttpFoundation\Response;

class FabricDescriptionApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param $fabricNature
     * @return JsonResponse
     */
    public function __invoke($fabricNature = null): JsonResponse
    {
        try {
            $fabric_composition_data = NewFabricComposition::with(['newFabricCompositionDetails.yarnComposition'])
                ->when($fabricNature, function ($query) use ($fabricNature) {
                    $query->where("fabric_nature_id", $fabricNature);
                })
                ->get()
                ->map(function ($fabric_composition) {
                    $composition = '';
                    $first_key = $fabric_composition->newFabricCompositionDetails->keys()->first();
                    $last_key = $fabric_composition->newFabricCompositionDetails->keys()->last();
                    $fabric_composition->newFabricCompositionDetails->each(function ($fabric_item, $key) use (&$composition, $first_key, $last_key, $fabric_composition) {
                        $composition .= ($key === $first_key) ? "{$fabric_composition->construction} [" : '';
                        $composition .= "{$fabric_item->yarnComposition->yarn_composition} {$fabric_item->percentage}%";
                        $composition .= ($key !== $last_key) ? ', ' : ']';
                    });

                    return [
                        "id" => $fabric_composition->id,
                        "composition" => $composition,
                        "gsm" => $fabric_composition->gsm,
                    ];
                });

            return response()->json($fabric_composition_data, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }
}
