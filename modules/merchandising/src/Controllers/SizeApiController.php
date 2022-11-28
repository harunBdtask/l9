<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Requests\SizeRequest;
use Symfony\Component\HttpFoundation\Response;

class SizeApiController extends Controller
{
    /**
     * @param SizeRequest $request
     * @param Size $color
     * @return JsonResponse
     */
    public function __invoke(SizeRequest $request, Size $color): JsonResponse
    {
        try {
            $color->fill($request->all())->save();

            return response()->json([
                "message" => "Size Added Successfully",
                "status" => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                "message" => "Something Went Wrong : {$exception->getMessage()}",
                "status" => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
