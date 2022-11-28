<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\ProTracker;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class BudgetCostingDetailsController extends Controller
{

    public function get(Request $request): JsonResponse
    {
        $orderId = $request->get('order');
        $order = Order::findOrFail($orderId);

        $costingDetails = BudgetCostingDetails::query()
            ->whereHas('budget', function ($query) use ($order) {
                $query->where('job_no', $order->job_no);
            })
            ->where('type', BudgetCostingDetails::FABRIC_COSTING)
            ->first()->details['details']['fabricForm'];
        $costingDetails = collect($costingDetails)
            ->where('bundle_card_default_cons', true)
            ->first();
        $greyConsDetails = $costingDetails ? collect($costingDetails['greyConsForm']['details'])->first() : [];

        $costingDetails = [
            'cons' => $costingDetails['grey_cons'] ?? 0,
            'gsm' => $costingDetails['gsm'] ?? 0,
            'dia' => $greyConsDetails['dia'] ?? 0,
        ];

        return response()->json([
            'data' => $costingDetails,
            'message' => 'Costing details fetched successfully.',
            'status' => Response::HTTP_OK,
        ]);
    }
}
