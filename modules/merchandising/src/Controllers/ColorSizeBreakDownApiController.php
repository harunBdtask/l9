<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class ColorSizeBreakDownApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $size_color_details = $request->get('data');
            $items_id = collect($size_color_details)->pluck("garments_item_id");
            $items = GarmentsItem::query()->whereIn("id", $items_id)->get();
            $all_colors_id = collect($size_color_details)->pluck("colors")->flatten()->unique()->toArray();
            $colors = Color::query()->whereIn("id", $all_colors_id)->get()->toArray();
            $all_sizes_id = collect($size_color_details)->pluck("sizes")->flatten()->unique()->toArray();
            $sizes = Size::query()->whereIn("id", $all_sizes_id)->get();
            $response = collect($size_color_details)->map(function ($value) use ($items, $colors, $sizes) {
                $item = collect($items)->where("id", $value['garments_item_id'])->first();
                $colors = collect($value['colors'])->map(function ($color) use ($colors) {
                    $color_name = collect($colors)->where("id", $color)->first();
                    return [
                        "color_id" => $color,
                        "name" => $color_name['name'] ?? '',
                    ];
                })->toArray();
                $sizes = collect($value['sizes'])->map(function ($size) use ($sizes) {
                    $size_name = collect($sizes)->where("id", $size)->first();

                    return [
                        "size_id" => $size,
                        "name" => $size_name['name'] ?? '',
                    ];
                })->toArray();

                return [
                    "item_id" => $value['garments_item_id'],
                    "item" => $item['name'] ?? '',
                    "colors" => $colors,
                    "sizes" => $sizes,
                ];
            });

            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
