<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use Symfony\Component\HttpFoundation\Response;

class StripController extends Controller
{
    public function commonData(): JsonResponse
    {
        return response()->json([
            'type' => 'success',
            'uoms' => UnitOfMeasurement::all(),
        ], Response::HTTP_OK);
    }
}
