<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\TrimsStore;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\CostingSheetService;

class TrimsReceiveDetailsService extends Controller
{
    public static function formatForMrrDetails($trimsReceiveDetails)
    {
        return $trimsReceiveDetails->map(function ($detail) {
            $order = Order::query()
                ->with('budgetData')
                ->where('style_name', $detail->trimsStoreReceive->style_name)
                ->first();

            $trimsCostData = CostingSheetService::formatTrimsCostData($order->budgetData);
            $itemName = $detail->itemGroup->item_group;
            $actualConsumption = collect($trimsCostData)->where('group_name', $itemName)->first();
            $sizeWiseBreakdownDetail = collect($actualConsumption['item']['breakdown']['details'])
                ->where('color_id', $detail->color_id)
                ->firstWhere('size_id', $detail->size_id);


            return [
                'trims_store_receive_id' => $detail->trims_store_receive_id,
                'trims_store_receive_detail_id' => $detail->id,
                'factory_id' => $detail->factory_id,
                'store_id' => $detail->store_id,
                'item_id' => $detail->item_id,
                'uom_id' => $detail->uom_id,
                'color_id' => $detail->color_id,
                'item_description' => $detail->item_description,
                'size_id' => $detail->size_id,
                'size' => $detail->size,
                'planned_garments_qty' => $detail->planned_garments_qty,
                'actual_qty' => $detail->booking_qty,
                'total_delivered_qty' => $detail->receive_qty,
                'rate' => $detail->rate,
                'amount' => (double)$detail->receive_qty * $detail->rate,
                'remarks' => null,
                'approval_shade_code' => $detail->approval_shade_code,
                'actual_consumption' => $sizeWiseBreakdownDetail['cons_gmts'] ?? 0,
                'total_consumption' => $sizeWiseBreakdownDetail['total_cons'] ?? 0,
            ];
        })->toArray();
    }
}
