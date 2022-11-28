<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\TrimsStore\TrimsInventory;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNAReports;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATask;
use Symfony\Component\HttpFoundation\Response;

class TNADateApiController extends Controller
{
    /**
     * @param TrimsBooking $booking
     * @return JsonResponse
     */
    public function __invoke(TrimsBooking $booking): JsonResponse
    {
        try {
            $orderId = Order::query()->where('style_name', $booking['style'])
                ->first()['id'] ?? null;

            $tnaTaskId = TNATask::query()
                ->where('task_short_name', 'TRD')
                ->first()['id'] ?? null;

            $tnaReport = TNAReports::query()
                ->where('task_id', $tnaTaskId)
                ->where('order_id', $orderId)
                ->first() ?? [];

            return response()->json([
                'message' => 'Fetch Trims Inventory Successfully',
                'data' => [
                    'tna_start_date' => $tnaReport['start_date'] ?? null,
                    'tna_end_date' => $tnaReport['finish_date'] ?? null,
                    'actual_start_date' => $tnaReport['actual_start_date'] ?? null,
                    'actual_end_date' => $tnaReport['actual_finish_date'] ?? null,
                ],
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
