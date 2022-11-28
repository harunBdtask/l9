<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Bookings;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use Symfony\Component\HttpFoundation\Response;

class BudgetWiseCopyFabricBookingController extends Controller
{

    /**
     * @param Budget $budget
     * @return JsonResponse
     */
    public function __invoke(Budget $budget): JsonResponse
    {
        try {
            $budget->load('factory', 'buyer');

            return response()->json([
                'message' => 'Fetch budget data successfully',
                'data' => $budget,
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
