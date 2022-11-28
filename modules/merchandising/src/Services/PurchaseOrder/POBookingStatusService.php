<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PurchaseOrder;

use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class POBookingStatusService
{
    public static function statusUpdate($poNo, $colorId, $sizeId, $type)
    {
        $purchaseOrder = PurchaseOrder::query()
            ->with('poDetails')
            ->whereIn('po_no', explode(',', $poNo))
            ->get();

        foreach ($purchaseOrder as $po) {
            $exData = $po['booking_status'] ?? [];
            $prevData = collect($exData)->where('type', $type)
                ->where('color_id', $colorId)
                ->where('size_id', $sizeId)
                ->first();
            if (! $prevData) {
                $newData = [
                    'type' => $type,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                ];
                $exData[] = $newData;
            }
            $bookingDetails = collect($exData)->filter(function ($value) {
                return $value !== null;
            })->values();
            PurchaseOrder::where('po_no', $po['po_no'])->update([
                'booking_status' => $bookingDetails,
            ]);
        }
    }
}
