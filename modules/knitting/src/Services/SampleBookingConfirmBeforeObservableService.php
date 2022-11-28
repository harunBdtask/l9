<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class SampleBookingConfirmBeforeObservableService
{
    public static function format($sampleBooking): array
    {
        $fabricSalesOrder = FabricSalesOrder::query()
            ->where('booking_id', $sampleBooking->sample_booking_id)
            ->first();

        if ($fabricSalesOrder) {
            $umoId = UnitOfMeasurement::query()
                ->where('unit_of_measurement', 'kg')
                ->first()->id;

            $programUOM = null;
            $finishQty = $sampleBooking->total_qty;

            if ($sampleBooking->uom == $umoId) {
                $programUOM = $sampleBooking->uom_id;
            }

            return [
                'fabric_sales_order_id' => $fabricSalesOrder->id,
                'garments_item_id' => $sampleBooking->gmts_item_id,
                'breakdown_id' => $sampleBooking->id,
                'body_part_id' => $sampleBooking->body_part_id,
                'color_type_id' => $sampleBooking->color_type_id,
                'fabric_description' => null,
                'fabric_composition_id' => null,
                'fabric_gsm' => $sampleBooking->gsm,
                'fabric_dia' => $sampleBooking->dia,
                'dia_type_id' => $sampleBooking->dia_type,
                'color' => $sampleBooking->color_id,
                'color_range' => null,
                'cons_uom' => $sampleBooking->uom,
                'booking_qty' => $sampleBooking->total_qty,
                'average_price' => $sampleBooking->rate,
                'amount' => $sampleBooking->amount,
                'prog_uom' => $programUOM,
                'finish_qty' => $finishQty,
                'process_loss' => null,
                'gray_qty' => $finishQty,
                'process' => null,
                'remarks' => null,
            ];
        }

        return [];
    }
}
