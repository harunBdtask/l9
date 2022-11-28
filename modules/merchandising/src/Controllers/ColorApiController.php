<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ColorRequest;
use Symfony\Component\HttpFoundation\Response;

class ColorApiController extends Controller
{
    /**
     * @param ColorRequest $request
     * @param Color $color
     * @return JsonResponse
     */
    public function __invoke(ColorRequest $request, Color $color): JsonResponse
    {
        try {
            $color->fill($request->all())->save();

            return response()->json([
                "data" => $color,
                "message" => "Color Added Successfully",
                "status" => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                "message" => "Something Went Wrong : {$exception->getMessage()}",
                "status" => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
