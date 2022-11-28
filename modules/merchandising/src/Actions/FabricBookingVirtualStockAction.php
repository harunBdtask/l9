<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricVirtualStock;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class FabricBookingVirtualStockAction
{
    public function handle($fabricBooking)
    {
        if ($this->validateVariable($fabricBooking)) {
            $data = [
                'composition' => $fabricBooking['composition'],
                'construction' => $fabricBooking['construction'],
                'gsm' => $fabricBooking['gsm'],
                'gmt_color' => $fabricBooking['gmt_color'],
                'item_color' => $fabricBooking['item_color'],
                'color_type' => $fabricBooking['color_type_id'],
                'dia' => $fabricBooking['dia'] ?? 'N\A',
            ];
            $actualWorkOrderQty = $fabricBooking['wo_qty'] + $fabricBooking['adj_qty'];
            $availableStock = FabricVirtualStock::query()->where($data)->sum('stock');
            $stockCalculate = ($availableStock - $actualWorkOrderQty + $fabricBooking['moq_qty']) - ($fabricBooking['avl_stock_qty'] ?? 0);
            $virtualStock = FabricVirtualStock::query()->firstOrNew($data);
            $virtualStock->stock = $stockCalculate <= 0 ? 0 : $stockCalculate;
            $virtualStock->save();
        }

    }

    public function validateVariable($fabricBooking): bool
    {
        $booking = FabricBooking::query()->findOrFail($fabricBooking['booking_id']);
        $variableData = MerchandisingVariableSettings::query()
            ->where("factory_id", $booking->factory_id)
            ->where("buyer_id", $booking->buyer_id)
            ->first()->variables_details;
        return isset($variableData['moq_maintain']) && (int)$variableData['moq_maintain'] === 1;
    }
}
