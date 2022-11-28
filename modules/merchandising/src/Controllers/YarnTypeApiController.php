<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\CompositionType;

class YarnTypeApiController extends Controller
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        try {
            $composition_types = CompositionType::all()->map(function ($value) {
                return [
                    'id' => $value->name,
                    'text' => $value->name,
                ];
            });

            return response()->json($composition_types, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
