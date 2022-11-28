<?php

namespace SkylarkSoft\GoRMG\Dyeing\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;
use Symfony\Component\HttpFoundation\Response;

class PaymentBasisApiController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        try {
            $paymentBasis = collect(TextileOrder::PAYMENT_BASIS)
                ->map(function ($collection, $key) {
                    return [
                        'id' => $key,
                        'text' => $collection,
                    ];
                })->values();

            return response()->json([
                'message' => 'Fetch textile order payment basis successfully',
                'data' => $paymentBasis,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
