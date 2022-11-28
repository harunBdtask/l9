<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricReceiveReturn;

use Exception;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricBarcodeDetail;

class FabricBarcodeReceiveReturn implements FabricReceiveReturnContracts
{
    /**
     * @param FabricReceiveReturnStrategy $strategy
     * @return array
     * @throws Exception
     */
    public function handle(FabricReceiveReturnStrategy $strategy): array
    {
        $request = $strategy->getRequest();
        $code = $request->get('code');

        $detail = FabricBarcodeDetail::query()->where('code', $code)->first();

        if (!isset($detail)) {
            throw new Exception('Barcode not found');
        }

        if ($detail->status == FabricBarcodeDetail::USED) {
            throw new Exception('Barcode already scanned');
        }

        return [
            'unique_id' => $detail->unique_id,
            'fabric_receive_detail_id' => $detail->fabric_receive_detail_id,
            'fabric_barcode_detail_id' => $detail->id,
            'product_name' => $detail->item->item_name,
            'buyer_id' => $detail->buyer_id,
            'style_id' => $detail->style_id,
            'style_name' => $detail->style_name,
            'po_no' => $detail->po_no,
            'batch_no' => $detail->batch_no,
            'gmts_item_id' => $detail->gmts_item_id,
            'body_part_id' => $detail->body_part_id,
            'body_part' => $detail->body->name,
            'fabric_composition_id' => $detail->fabric_composition_id,
            'construction' => $detail->construction,
            'fabric_description' => $detail->fabric_description,
            'dia' => $detail->dia,
            'ac_dia' => $detail->ac_dia,
            'gsm' => $detail->gsm,
            'ac_gsm' => $detail->ac_gsm,
            'dia_type' => $detail->dia_type,
            'ac_dia_type' => $detail->ac_dia_type,
            'color' => $detail->fabricColor->name,
            'color_id' => $detail->color_id,
            'contrast_color_id' => $detail->contrast_color_id,
            'uom' => $detail->uom->unit_of_measurement,
            'uom_id' => $detail->uom_id,
            'return_qty' => $detail->qty,
            'rate' => $detail->rate,
            'amount' => number_format($detail->rate * $detail->qty, 4),
            'fabric_shade' => $detail->fabric_shade,
            'no_of_roll' => $detail->receiveDetail->no_of_roll,
            'store_id' => $detail->receive->store_id,
            'store_name' => $detail->receive->store->name,
            'floor' => $detail->floor->name,
            'floor_id' => $detail->floor_id,
            'room' => $detail->room->name,
            'room_id' => $detail->room_id,
            'rack' => $detail->rack->name,
            'rack_id' => $detail->rack_id,
            'shelf' => $detail->shelf->name,
            'shelf_id' => $detail->shelf_id,
            'remarks' => null,
            'color_type_id' => $detail->color_type_id,
            'booking_no' => $detail->receive->booking->unique_id,
            //            'current_stock' => $globalStock,
            'fabric_receive' => $detail->qty,
            //            'cumulative_return' => $detail->return_details_sum_return_qty ?? 0,
            //            'yet_to_issue' => $detail->issue_details_sum_issue_qty,
            //            'global_stock' => $globalStock,
            'mrr_no' => $detail->receive->receive_no,
            'receive_return_type' => 'barcode',
        ];
    }

    /**
     * @param FabricReceiveReturnStrategy $strategy
     * @return mixed
     */
    public function store(FabricReceiveReturnStrategy $strategy)
    {
        $request = $strategy->getRequest();
        $receiveReturn = $strategy->getReceiveReturnModel();

        // Barcode Destroy
        FabricBarcodeDetail::query()
            ->where('id', $request->get('fabric_barcode_detail_id'))
            ->first()->delete();

        $receiveDetail = $receiveReturn->details()->findOrNew($request->get('id') ?? null);
        $receiveDetail->fill($request->all())->save();

        return $receiveDetail;
    }
}
