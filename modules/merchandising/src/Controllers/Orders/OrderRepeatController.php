<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers\Orders;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Actions\FeatureVersionAction;
use SkylarkSoft\GoRMG\Merchandising\Features;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\OrderService;
use SkylarkSoft\GoRMG\TimeAndAction\Services\TNAReportService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OrderRepeatController
{
    /**
     * @throws Throwable
     */
    public function store(Request $request, FeatureVersionAction $featureVersionAction): JsonResponse
    {
        try {
            DB::beginTransaction();
            $style = $request->get('style');
            $order = Order::query()
                ->where('style_name', $style)
                ->first();

            $repeatedOrdersCount = Order::query()
                ->where('repeated_style', $style)
                ->count();

            $repeatedStyle = str_pad((string)($repeatedOrdersCount + 1), 2, '0', STR_PAD_LEFT);
            $repeatOrder = $order->replicate();
//            $repeatOrder['job_no'] = OrderService::generateUniqueId();
            $repeatOrder['style_name'] = $style . '-' . $repeatedStyle;
            $repeatOrder['repeated_style'] = $style;
            $repeatOrder['is_repeated'] = true;
            $repeatOrder->save();

            $parentOrderPo = PurchaseOrder::query()
                ->where('order_id', $order->id)
                ->where('factory_id', $order->factory_id)
                ->get();

            foreach ($parentOrderPo as $orderPo) {
                $repeatPoNo = str_pad((string)($repeatedOrdersCount + 1), 2, '0', STR_PAD_LEFT);
                $repeatOrderPo = $orderPo->replicate();
                $repeatOrderPo['order_id'] = $repeatOrder->id;
                $repeatOrderPo['is_approved'] = 0;
                $repeatOrderPo['po_receive_date'] = null;
                $repeatOrderPo['ex_factory_date'] = null;
                $repeatOrderPo['po_no'] = $repeatOrderPo->po_no . '-' . $repeatPoNo;
                $repeatOrderPo->save();

                $colorSizeDetails = PoColorSizeBreakdown::query()
                    ->where('purchase_order_id', $orderPo->id)
                    ->get()
                    ->map(function ($colorSizeData) use ($repeatOrderPo) {
                        $colorSizeData['order_id'] = $repeatOrderPo->order_id;
                        $colorSizeData['purchase_order_id'] = $repeatOrderPo->id;
                        return $colorSizeData;
                    })->toArray();

                $repeatOrderPo->poDetails()->createMany($colorSizeDetails);
                $featureVersionAction->handle(Features::ORDER, $repeatOrder->id);
            }

            DB::commit();

            return response()->json([
                'status' => 'Success',
                'message' => 'Data Saved Successfully',
                'data' => $repeatOrder,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
