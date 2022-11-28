<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;

class CostingSummerApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param $quotation
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($quotation): \Illuminate\Http\JsonResponse
    {
        try {
            $costings_summery = CostingDetails::where("price_quotation_id", $quotation)->get();

            return response()->json($costings_summery, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }
}
