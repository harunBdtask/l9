<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\ColorType;

class ColorTypesApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $color_types = ColorType::query()->select('*', 'color_types as text')->get();
            $color_types = $color_types->map(function ($collection) {
                if (strtoupper($collection->text) == 'SOLID') {
                    $collection->default = 1;
                }
                return $collection;
            });
            return response()->json($color_types, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
