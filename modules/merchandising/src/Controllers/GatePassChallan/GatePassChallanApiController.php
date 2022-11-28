<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\GatePassChallan;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use Symfony\Component\HttpFoundation\Response;

class GatePassChallanApiController extends Controller
{
    /**
     * @param $styleId
     * @return JsonResponse
     */
    public function getStyleWiseColor(Request $request): JsonResponse
    {
        $styleWisePOColors = [];
        if ($request->get('status') == 2) {
            $styleWisePOColors = PoColorSizeBreakdown::query()
                ->where('order_id', $request->get('styleId'))
                ->pluck('colors')
                ->collapse()
                ->unique()
                ->toArray();
        }

        $colors = Color::query()
            ->when($request->get('status') == 2, function ($query) use ($styleWisePOColors) {
                return $query->whereIn('id', $styleWisePOColors);
            })
            ->get(["id", "name as text"]);
        return response()->json($colors, Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function getGarmentsSample(): JsonResponse
    {
        $samples = GarmentsSample::all(['id', 'name as text']);
        return response()->json($samples);
    }

    /**
     * @param $styleId
     * @return JsonResponse
     */
    public function getStyleWiseSize(Request $request): JsonResponse
    {
        $styleWisePOColors = [];

        if ($request->get('status') == 2) {
            $styleWisePOColors = PoColorSizeBreakdown::query()
                ->where('order_id', $request->get('styleId'))
                ->pluck('sizes')
                ->collapse()
                ->unique()
                ->toArray();
        }

        $colors = Size::query()
            ->when($request->get('status') == 2, function ($query) use ($styleWisePOColors) {
                return $query->whereIn('id', $styleWisePOColors);
            })
            ->get(["id", "name as text"]);
        return response()->json($colors, Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function getFabricComposition(): JsonResponse
    {
        $compositions = NewFabricComposition::query()->get()
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
                    "text" => $composition,
                    "construction" => $fabric_composition->construction,
                    "gsm" => $fabric_composition->gsm,
                    "dia" => $fabric_composition->finish_fabric_dia,
                ];
            });
        return response()->json($compositions, Response::HTTP_OK);
    }
}
