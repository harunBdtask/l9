<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\API\Libraries;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Symfony\Component\HttpFoundation\Response;

class ColorsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $colors = Color::query()
            ->where('name', 'LIKE', "%$request->search%")
            ->get(['id', 'name as text']);

            return response()->json(['data' => $colors], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
