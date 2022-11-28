<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;

class OrderWiseColorApiController extends Controller
{
    public function __invoke($orderId, $itemId)
    {
        try {
            $po_details = PoColorSizeBreakdown::where([
                "order_id" => $orderId,
                "garments_item_id" => $itemId,
            ])->get();
            $colors_id = collect($po_details)->pluck("colors")->flatten()->unique();
            $get_colors = Color::whereIn("id", $colors_id)->get();
            $colors = collect($colors_id)
                ->map(function ($value) use ($get_colors) {
                    $color_name = collect($get_colors)->where("id", $value)->first();

                    return [
                        "id" => $value,
                        "text" => $color_name['name'],
                    ];
                })->values();
            return response()->json($colors, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
