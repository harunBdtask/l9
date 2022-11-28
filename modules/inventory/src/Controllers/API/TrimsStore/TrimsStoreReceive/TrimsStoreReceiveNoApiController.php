<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsStoreReceive;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsStore\TrimsStoreReceive\TrimsStoreReceive;
use Symfony\Component\HttpFoundation\Response;

class TrimsStoreReceiveNoApiController extends Controller
{
    public function __invoke($bookingNo): JsonResponse
    {
        try {
            $receiveNos = TrimsStoreReceive::query()
                ->where('booking_no', $bookingNo)
                ->get([
                    'id',
                    'unique_id as text'
                ]);

            return response()->json([
                'data' => $receiveNos ?? [],
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
