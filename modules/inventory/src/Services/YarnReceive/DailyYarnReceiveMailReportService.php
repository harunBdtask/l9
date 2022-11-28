<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnReceive;

use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;

class DailyYarnReceiveMailReportService
{
    public function generate($date)
    {
        return YarnReceiveDetail::query()
            ->whereHas('yarnReceive', function ($query) use ($date) {
                $query->where('receive_date', $date);
            })
            ->with(['yarnReceive.wo.details.order.purchaseOrders:id,order_id,po_no,ex_factory_date', 'yarnReceive.wo.buyer:id,name', 'type'])
            ->get()
            ->map(function ($item) {
                $purchaseOrder = [];

                if ($item->yarnReceive->receive_basis == 'wo') {
                    $purchaseOrder = collect($item->yarnReceive->wo->details ?? [])
                        ->where('yarn_count_id', $item->yarn_count_id)
                        ->where('yarn_type', $item->type->name)
                        ->where('yarn_composition_id', $item->yarn_composition_id)
                        ->where('yarn_color', $item->yarn_color)
                        ->where('uom_id', $item->uom_id)
                        ->first();
                }

                $totalReceive = YarnReceiveDetail::query()
                    ->where(YarnItemAction::itemCriteria($item))
                    ->sum('receive_qty');

                return [
                    'buyer_name' => $item->yarnReceive->wo->buyer->name ?? '',
                    'reference_no' => $purchaseOrder['unique_id'] ?? '',
                    'style_name' => $purchaseOrder['style_name'] ?? '',
                    'wo_qty' => $purchaseOrder['wo_qty'] ?? '',
                    'receive_no' => $item->yarnReceive->receive_no,
                    'today_receive' => $item->receive_qty,
                    'total_receive' => $totalReceive,
                    'po_nos' => collect($purchaseOrder['order']['purchaseOrders'] ?? [])->pluck('po_no')->join(', '),
                    'pcd_date' => $purchaseOrder['order']['pcd_date'] ?? '',
                    'ex_factory_date' => collect($purchaseOrder['order']['purchaseOrders'] ?? [])->first()['ex_factory_date'] ?? '',
                ];
            });
    }
}
