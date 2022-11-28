<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Symfony\Component\HttpFoundation\Response;

class TrimsContrastColorApiController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $colors = Color::with('garmentsColor')
                ->where([
                    'tag' => $request->get('tag'),
                    'style' => $request->get('style'),
                    'status' => Color::COLOR_TYPE['fabric_color'],
                ])->whereIn('parent_id', $request->get('colors'))->get()->toArray();

            $response = collect($request->get('colors'))
                ->map(function ($value) use ($colors) {
                    $fabric_colors = collect($colors)->where('parent_id', $value)->map(function ($contrast) {
                        return [
                            'fabric_color_id' => $contrast['id'] ?? '',
                            'fabric_color_name' => $contrast['name'] ?? '',
                        ];
                    })->values();
                    $color = collect($colors)->where('garments_color.id', $value)->first();

                    return [
                        'color_id' => $value,
                        'color_name' => isset($color) ? $color['garments_color']['name'] : '',
                        'fabric_colors' => collect($fabric_colors)->first()['fabric_color_name'] ?? null,
                    ];
                });

            return response()->json($response, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
