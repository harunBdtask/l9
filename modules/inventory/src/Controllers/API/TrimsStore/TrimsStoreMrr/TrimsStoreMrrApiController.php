<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreMrr;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreMrr\TrimsStoreMrr;

class TrimsStoreMrrApiController extends Controller
{
    public function __invoke($bookingId): JsonResponse
    {
        try {
            $binNo = TrimsStoreMrr::query()
                ->where('booking_id', $bookingId)
                ->get([
                    'id',
                    'mrr_no as text'
                ]);

            return response()->json([
                'data' => $binNo ?? [],
                'status' => Response::HTTP_OK,
                'message' => \SUCCESS_MSG,
            ], Response::HTTP_OK);

        } catch (Exception $exception) {

            return response()->json([
                'error' => $exception->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => \SOMETHING_WENT_WRONG,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }
}
