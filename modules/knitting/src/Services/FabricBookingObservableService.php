<?php

namespace SkylarkSoft\GoRMG\Knitting\Services;

use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder;
use SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrderDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class FabricBookingObservableService
{
    public static function format($fabricBookingBreakdown, $bookingId): array
    {
        $fabricSalesOrder = FabricSalesOrder::query()
            ->where('booking_id', $bookingId)
            ->first();

        if($fabricSalesOrder) {
            $umoId = UnitOfMeasurement::query()
                ->where('unit_of_measurement', 'kg')
                ->first()->id;

            $finishQty = $fabricBookingBreakdown->total_qty;
            $programUOM = null;

            if ($fabricBookingBreakdown->uom == $umoId) {
                $programUOM = $fabricBookingBreakdown->uom_id;
            }

            $fabricBudgetDetails = $fabricBookingBreakdown->budget->fabricCosting->details['details']['fabricForm'];
            $fabricDescription = $fabricBookingBreakdown->construction . " [" . $fabricBookingBreakdown->composition . "]";
            $fabricBudget = collect($fabricBudgetDetails)
                ->where('body_part_id', $fabricBookingBreakdown->body_part_id)
                ->where('color_type_id', $fabricBookingBreakdown->color_type_id)
                ->where('fabric_composition_value', $fabricDescription)
                ->first();

            return [
                'fabric_sales_order_id' => $fabricSalesOrder->id,
                'garments_item_id' => $fabricBudget['garment_item_id'],
                'breakdown_id' => $fabricBookingBreakdown->id,
                'body_part_id' => $fabricBookingBreakdown->body_part_id,
                'color_type_id' => $fabricBookingBreakdown->color_type_id,
                'fabric_description' => $fabricDescription,
                'fabric_composition_id' => $fabricBudget['fabric_composition_id'],
                'fabric_gsm' => $fabricBookingBreakdown->gsm,
                'fabric_dia' => $fabricBookingBreakdown->dia,
                'dia_type_id' => $fabricBookingBreakdown->dia_type,
                'color' => $fabricBookingBreakdown->color_id,
                'color_range' => null,
                'cons_uom' => $fabricBookingBreakdown->uom,
                'booking_qty' => $fabricBookingBreakdown->total_qty,
                'average_price' => $fabricBookingBreakdown->rate,
                'amount' => $fabricBookingBreakdown->amount,
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
