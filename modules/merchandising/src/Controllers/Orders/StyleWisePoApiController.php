<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\POFileModel;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use Symfony\Component\HttpFoundation\Response;

class StyleWisePoApiController extends Controller
{
    /**
     * @param $style
     * @return JsonResponse
     */
    public function __invoke($style): JsonResponse
    {
        try {
            $styleWisePo = POFileModel::query()
                ->where('style', $style)
                ->get(['po_no'])->map(function ($collection){
                    return [
                        'id'=>$collection->po_no,
                        'text'=>$collection->po_no
                    ];
                });
            $usedPo = Order::query()
                ->with(['purchaseOrders:id,po_no,order_id'])
                ->where('style_name', $style)
                ->get()
                ->flatMap(function ($collection) {
                    return collect($collection->purchaseOrders)->pluck('po_no');
                });
            $response = [
                'styleWisePo' => $styleWisePo,
                'usedPo' => $usedPo,
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
