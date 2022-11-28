<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;
use Symfony\Component\HttpFoundation\Response;

class DyesAndChemicalsItemWiseLifeEndDaysApiController extends Controller
{
    /**
     * @param $itemId
     * @return JsonResponse
     */
    public function __invoke($itemId): JsonResponse
    {
        try {
            $itemLifeEndDays = DyesChemicalTransaction::query()->where('item_id', $itemId)->get()
                ->pluck('life_end_days')
                ->unique();

            return response()->json($itemLifeEndDays, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
