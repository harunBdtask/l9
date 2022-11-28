<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;

class CostingDetailsApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param $quotation
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($quotation, $type): \Illuminate\Http\JsonResponse
    {
        try {
            $costings_details = CostingDetails::where("price_quotation_id", $quotation)
                ->where("type", $type)
                ->first();

            return response()->json($costings_details, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }
}
