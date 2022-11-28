<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Budgets;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Actions\CopyPriceQuotationCostingsForBudget;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\AssociateVersionWithOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\FileUploadRemoveService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OrderWiseBudgetCopyController extends Controller
{

    /**
     * @param Order $order
     * @return JsonResponse
     */
    public function __invoke(Order $order): JsonResponse
    {
        try {
            $order->load('purchaseOrders',
                'factory',
                'buyer',
                'currency',
                'season',
                'productCategory',
                'priceQuotation',
                'priceQuotation.costingDetails',
                'productDepartment',
                'priceQuotation.incoterm',
                'priceQuotation.buyingAgent',
                'buyingAgent',
                'purchaseOrders.poDetails');

            return response()->json([
                'message' => 'Budget Created successfully',
                'data' => $order,
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
