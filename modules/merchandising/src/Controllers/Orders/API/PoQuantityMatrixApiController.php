<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\POFileModel;
use Symfony\Component\HttpFoundation\Response;

class PoQuantityMatrixApiController extends Controller
{
    /**
     * @param $po_no
     * @return JsonResponse
     */
    public function readQuantityMatrix($po_no): JsonResponse
    {
        try {
            $po_quantity_matrix = POFileModel::query()->where("po_no", $po_no)->first();
            return response()->json($po_quantity_matrix, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $po_no
     * @return JsonResponse
     */
    public function readColorSizePoFile($po_no): JsonResponse
    {
        try {
            $po_quantity_matrix = POFileModel::query()->where("po_no", $po_no)->firstOrFail();
            $items = collect($po_quantity_matrix->quantity_matrix)
                ->pluck("item_id")
                ->unique()
                ->values()
                ->map(function ($value) use ($po_quantity_matrix) {

                    $colors = collect($po_quantity_matrix->quantity_matrix)
                        ->where("item_id", $value)->pluck("color_id")
                        ->unique()
                        ->values();
                    $sizes = collect($po_quantity_matrix->quantity_matrix)
                        ->where("item_id", $value)
                        ->pluck("size_id")
                        ->unique()
                        ->values();

                    return [
                        "garments_item_id" => $value,
                        "colors" => $colors,
                        "sizes" => $sizes,
                    ];
                })->toArray();

            return response()->json($items, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
