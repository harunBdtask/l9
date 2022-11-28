<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreBinCard;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreBinCard\TrimsStoreBinCard;
use Symfony\Component\HttpFoundation\Response;

class TrimsStoreBinCardNoApiController extends Controller
{
    public function __invoke($bookingId): JsonResponse
    {
        try {
            $binCardNos = TrimsStoreBinCard::query()
                ->where('booking_id', $bookingId)
                ->get([
                    'id',
                    'unique_id as text'
                ]);

            return response()->json([
                'data' => $binCardNos,
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
