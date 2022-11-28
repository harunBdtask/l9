<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricTransfer;

use Exception;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricBarcodeDetail;

class FabricBarcodeTransfer implements FabricTransferContracts
{
    /**
     * @param FabricTransferStrategy $strategy
     * @return array
     * @throws Exception
     */
    public function handle(FabricTransferStrategy $strategy): array
    {
        $request = $strategy->getRequest();
        $code = $request->get('code');
        $storeId = $request->get('store_id');

        $detail = FabricBarcodeDetail::query()->where([
            'code' => $code,
            'store_id' => $storeId,
        ])->first();

        if (!isset($detail)) {
            throw new Exception('Barcode not found');
        }

        if ($detail->status == FabricBarcodeDetail::USED) {
            throw new Exception('Barcode already scanned');
        }

//        $summery = (new FabricStockSummaryService())->summary($detail);
//        $rate = $summery->receive_amount / $summery->receive_qty;

        return [
            'unique_id' => $detail->unique_id,
            'po_no' => $detail->po_no,
            'buyer_id' => $detail->buyer_id,
            'buyer_name' => $detail->buyer->name,
            'style_id' => $detail->style_id,
            'style_name' => $detail->style_name,
            'batch_no' => $detail->batch_no,
            'booking_no' => $detail->receive->booking->unique_id,
            'company_name' => $detail->receive->factory->factory_name,
            'gmts_item_id' => $detail->gmts_item_id,
            'gmts_item_name' => $detail->item->name,
            'body_part_id' => $detail->body_part_id,
            'body_part_value' => $detail->body->name,
            'dia' => $detail->dia,
            'gsm' => $detail->gsm,
            'dia_type' => $detail->dia_type,
            'construction' => $detail->construction,
            'fabric_composition_id' => $detail->fabric_composition_id,
            'fabric_description' => $detail->fabric_description,
            'color_id' => $detail->color_id,
            'color' => $detail->fabricColor->name,
            'contrast_color_id' => $detail->contrast_color_id,
            'contrast_color_value' => null,
            'uom_id' => $detail->uom_id,
            'uom_name' => $detail->uom->unit_of_measurement,
            'balance_qty' => $detail->qty,
            'transfer_qty' => $detail->qty,
            'rate' => $detail->rate,
            'amount' => number_format($detail->qty * $detail->rate, 4),
            'fabric_shade' => $detail->fabric_shade,
            'no_of_roll' => $detail->receiveDetail->no_of_roll,
            'store_id' => $detail->store_id,
            'floor_id' => $detail->floor_id,
            'floor_name' => $detail->floor->name,
            'room_id' => $detail->room_id,
            'room_name' => $detail->room->name,
            'rack_id' => $detail->rack_id,
            'rack_name' => $detail->rack->name,
            'shelf_id' => $detail->shelf_id,
            'shelf_name' => $detail->shelf->name,
            'color_type_id' => $detail->color_type_id,
            'ref_no' => null,
            'remarks' => null,
        ];
    }

    public function store(FabricTransferStrategy $strategy)
    {
        // TODO: Implement store() method.
    }
}
