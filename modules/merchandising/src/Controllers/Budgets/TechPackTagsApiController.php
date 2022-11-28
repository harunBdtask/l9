<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Symfony\Component\HttpFoundation\Response;

class TechPackTagsApiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $budget = Budget::query()->findOrFail($request->get('budgetId'));
            $colors = Color::query()->where('style', $budget->style_name)
                ->pluck('tag')->unique()->map(function ($value) use ($budget) {
                    return [
                        'id' => $value,
                        'text' => $value,
                        'style' => $budget->style_name,
                    ];
                })->values();

            return response()->json($colors, Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
