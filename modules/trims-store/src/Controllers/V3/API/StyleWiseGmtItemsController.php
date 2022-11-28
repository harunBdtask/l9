<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class StyleWiseGmtItemsController extends Controller
{
    public function __invoke(Order $order): JsonResponse
    {
        try {
            $garmentItemId = collect($order['item_details']['details'])
                ->pluck('item_id')
                ->join(', ') ?? null;

            $garmentItemName = collect($order['item_details']['details'])
                ->pluck('item_name')
                ->join(', ') ?? null;

            return response()->json([
                'message' => 'Trims store receive detail stored successfully',
                'data' => [
                    'po_no' => $order['po_no'],
                    'garments_item_id' => $garmentItemId,
                    'garments_item_name' => $garmentItemName,
                ],
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
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
