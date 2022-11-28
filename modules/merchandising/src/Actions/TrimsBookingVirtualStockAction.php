<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricVirtualStock;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsVirtualStock;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class TrimsBookingVirtualStockAction
{
    public function handle($trimsBooking)
    {
        if ($this->validateVariable($trimsBooking)) {
            $data = [
                'item_color' => $trimsBooking['item_color'],
                'item_description' => $trimsBooking['item_description'],
                'item_size' => $trimsBooking['item_size'],
                'stock' => $trimsBooking['item_color'],
            ];
            $actualWorkOrderQty = $trimsBooking['wo_qty'];
            $availableStock = TrimsVirtualStock::query()->where([
                'item_id' => $trimsBooking['item_id'],
                'item_color' => $trimsBooking['item_color'],
            ])->sum('stock');

            $stockCalculate = ($availableStock - $actualWorkOrderQty + $trimsBooking['moq_qty']) - ($trimsBooking['avl_stock_qty'] ?? 0);
            $virtualStock = TrimsVirtualStock::query()->firstOrNew([
                'item_id' => $trimsBooking['item_id'],
                'item_color' => $trimsBooking['item_color'],
            ], $data);
            $virtualStock->stock = $stockCalculate <= 0 ? 0 : $stockCalculate;

            // TODO;
            //$virtualStock->save();
        }

    }

    public function validateVariable($trimsBooking): bool
    {
        $booking = TrimsBooking::query()->findOrFail($trimsBooking['booking_id']);
        $variableData = MerchandisingVariableSettings::query()
            ->where("factory_id", $booking->factory_id)
            ->where("buyer_id", $booking->buyer_id)
            ->first()->variables_details;
        return isset($variableData['moq_maintain']) && (int)$variableData['moq_maintain'] === 1;
    }
}
