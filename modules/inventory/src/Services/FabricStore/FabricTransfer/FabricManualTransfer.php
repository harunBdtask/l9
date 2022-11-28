<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricTransfer;

use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;

class FabricManualTransfer implements FabricTransferContracts
{
    public function handle(FabricTransferStrategy $strategy)
    {
        $request = $strategy->getRequest();
        $orderNo = $request->get('order_no');
        $bookingId = $request->get('booking_id');
        $batchNo = $request->get('batch_no');
        $buyerId = $request->get('buyer_id');
        $gmtsItemId = $request->get('gmts_item_id');
        $storeId = $request->get('store_id');

        return FabricReceiveDetail::query()->whereHas('receive', function (Builder $builder) {
            $builder->where('status', FabricReceive::APPROVE);
        })->where('store_id', $storeId)->whereHas('receive',
            function (Builder $query) use ($orderNo, $bookingId) {
                $query->where('status', FabricReceive::APPROVE)
                    ->when($orderNo, function (Builder $query) use ($orderNo) {
                        $query->whereJsonContains('po_no', $orderNo);
                    })->when($bookingId, function (Builder $query) use ($bookingId) {
                        $query->whereHas('booking', function ($query) use ($bookingId) {
                            $query->where('unique_id', $bookingId);
                        });
                    });
            }
        )->when($batchNo, function ($query) use ($batchNo) {
            $query->where('batch_no', $batchNo);
        })->when($buyerId, function ($query) use ($buyerId) {
            $query->where('buyer_id', $buyerId);
        })->when($gmtsItemId, function ($query) use ($gmtsItemId) {
            $query->where('gmts_item_id', $gmtsItemId);
        })->get()->map(function ($detail) {
            $summery = (new FabricStockSummaryService())->summary($detail);
            $rate = $summery->receive_amount / $summery->receive_qty;

            return [
                'unique_id' => $detail->unique_id,
                'po_no' => $detail->receive->po_no,
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
                'body_part_value' => $detail->bookingDetail->body_part_value,
                'dia' => $detail->dia,
                'ac_dia' => $detail->ac_dia,
                'gsm' => $detail->gsm,
                'ac_gsm' => $detail->ac_gsm,
                'dia_type' => $detail->dia_type,
                'ac_dia_type' => $detail->ac_dia_type,
                'construction' => $detail->construction,
                'fabric_composition_id' => $detail->fabric_composition_id,
                'fabric_description' => $detail->fabric_description,
                'color_id' => $detail->color_id,
                'color' => $detail->fabricColor->name,
                'contrast_color_id' => $detail->contrast_color_id,
                'contrast_color_value' => null,
                'uom_id' => $detail->uom_id,
                'uom_name' => $detail->uom->unit_of_measurement,
                'balance_qty' => $summery->balance,
                'transfer_qty' => $summery->balance,
                'rate' => $rate,
                'amount' => $summery->balance * $rate,
                'fabric_shade' => $detail->fabric_shade,
                'no_of_roll' => $detail->no_of_roll,
                'store_id' => null,
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
        });
    }

    public function store(FabricTransferStrategy $strategy)
    {
        // TODO: Implement store() method.
    }
}
