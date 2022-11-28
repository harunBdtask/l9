<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\ReportService;

use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;

class DailyFinishFabricReceiveMailReportService
{
    public function getReportData($date)
    {
        return FabricReceive::query()
            ->with('details.order.purchaseOrders')
            ->whereDate('receive_date', $date)
            ->get()
            ->flatMap(function ($item) {
                return $item->details->map(function ($details) use ($item) {
                    $exFactoryDate = collect($details->order->purchaseOrders ?? [])->first()['ex_factory_date'] ?? '';
                    $bookingDate = null;
                    if ($details->receivable_id) {
                        $bookingDate = FabricBooking::query()
                            ->where('id', $details->receivable_id)
                            ->first()->booking_date ?? '';
                    }
                    $totalReceive = FabricReceiveDetail::query()
                        ->where('buyer_id', $details->buyer_id)
                        ->where('unique_id', $details->unique_id)
                        ->where('style_name', $details->style_name)
                        ->sum('receive_qty');

                    return [
                        'buyer_name' => $details->buyer->name ?? '',
                        'reference_no' => $details->unique_id,
                        'style_order_no' => $details->style_name,
                        'po_no' => $details->po_no,
                        'order_qty' => $details->order->pq_qty_sum ?? '',
                        'fab_cons' => null,
                        'receive_no' => $item->receive_no,
                        'receive_qty' => $details->receive_qty,
                        'total_receive_qty' => $totalReceive,
                        'bal_receive_qty' => $totalReceive - $details->receive_qty,
                        'pcd_date' => $details->pcd_date,
                        'ex_factory_date' => $exFactoryDate,
                        'booking_date' => $bookingDate,
                        'fabric_otd' => null,
                    ];
                });
            });
    }
}
