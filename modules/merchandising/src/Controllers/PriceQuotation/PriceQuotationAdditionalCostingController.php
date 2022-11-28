<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\PriceQuotation;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PriceQuotationAdditionalCostingController extends Controller
{
    public function index(PriceQuotation $priceQuotation)
    {
        return view('merchandising::price_quotation.additional_costing');
    }

    /**
     * @param Request $request
     * @param PriceQuotation $priceQuotation
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request $request, PriceQuotation $priceQuotation): JsonResponse
    {
        try {
            DB::beginTransaction();
            $priceQuotation->update([
                'additional_costing' => $request->get('additionalCosting')
            ]);
            DB::commit();
            return response()->json([
                'data' => $priceQuotation,
                'message' => 'Successfully additional costing added',
                'status' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param PriceQuotation $priceQuotation
     * @return JsonResponse
     */
    public function edit(PriceQuotation $priceQuotation): JsonResponse
    {
        try {
            $priceQuotation = $priceQuotation->only(['id', 'quotation_id', 'additional_costing']);

            return response()->json([
                'data' => $priceQuotation,
                'message' => 'Successfully additional costing added',
                'status' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
