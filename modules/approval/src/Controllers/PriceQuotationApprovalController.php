<?php


namespace SkylarkSoft\GoRMG\Approval\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Services\PriceQuotationApprovalService;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PriceQuotationApprovalController extends Controller
{
    const PAGE_NAME = 'Price Quotation';

    public function index()
    {
        return view('approval::approvals.modules.priceQuotation');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {

            $priceQuotation = PriceQuotationApprovalService::for(Approval::PRICE_QUOTATION)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->response();

            $response = [
                'data' => $priceQuotation,
                'status' => Response::HTTP_OK,
                'message' => 'price quotation fetched successfully',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $priceQuotation = PriceQuotationApprovalService::for(Approval::PRICE_QUOTATION)
                ->setRequest($request)
                ->setBuyer($request->get('buyer'))
                ->store();
            $response = [
                'data' => $priceQuotation,
                'status' => Response::HTTP_OK,
                'message' => 'price quotation updated successfully',
            ];
            DB::commit();

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
