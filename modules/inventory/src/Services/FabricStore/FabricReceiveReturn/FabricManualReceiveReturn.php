<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricReceiveReturn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\BalanceQty;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;

class FabricManualReceiveReturn implements FabricReceiveReturnContracts
{

    const MRR_NO = 'mrr_no';
    const CHALLAN_NO = 'challan_no';
    const BATCH_NO = 'batch_no';
    const BOOKING_NO = 'booking_no';

    /**
     * @param FabricReceiveReturnStrategy $strategy
     * @return Builder[]|Collection|\Illuminate\Support\Collection
     */
    public function handle(FabricReceiveReturnStrategy $strategy)
    {
        $request = $strategy->getRequest();
        $receive_id = $request->get('receive_id');
        $search_by = $request->get('search_by');
        $mrr_no = $request->get('mrr_no');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        return FabricReceiveDetail::query()
            ->withSum('issueDetails', 'issue_qty')
            ->withSum('returnDetails', 'return_qty')
            ->where('receive_id', $receive_id)
            ->when($start_date && $end_date, function (Builder $query) use ($start_date, $end_date) {
                $query->whereBetween('receive_date', [$start_date, $end_date]);
            })
            ->when($mrr_no, function (Builder $query) use ($search_by, $mrr_no) {
                $query->when($search_by === self::MRR_NO, function (Builder $query) use ($mrr_no) {
                    $query->whereHas('receive', function (Builder $query) use ($mrr_no) {
                        $query->where('receive_no', $mrr_no);
                    });
                })->when($search_by === self::CHALLAN_NO, function (Builder $query) use ($mrr_no) {
                    $query->whereHas('receive', function (Builder $query) use ($mrr_no) {
                        $query->where('receive_challan', $mrr_no);
                    });
                })->when($search_by === self::BATCH_NO, function (Builder $query) use ($mrr_no) {
                    $query->where('batch_no', $mrr_no);
                })->when($search_by === self::BOOKING_NO, function (Builder $query) use ($mrr_no) {
                    $query->whereHas('receive', function (Builder $query) use ($mrr_no) {
                        $query->whereHas('booking', function ($query) use ($mrr_no) {
                            $query->where('unique_id', $mrr_no);
                        });
                    });
                });
            })
            ->get()->map(function ($detail) {
                $globalStock = (new BalanceQty())->balance($detail);

                return [
                    'unique_id' => $detail->unique_id,
                    'fabric_receive_detail_id' => $detail->id,
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
                    'dia_type' => $detail->dia_type,
                    'color' => $detail->fabricColor->name,
                    'color_id' => $detail->color_id,
                    'contrast_color_id' => $detail->contrast_color_id,
                    'uom' => $detail->uom->unit_of_measurement,
                    'uom_id' => $detail->uom_id,
                    'return_qty' => null,
                    'rate' => $detail->rate,
                    'amount' => null,
                    'fabric_shade' => $detail->fabric_shade,
                    'no_of_roll' => $detail->no_of_roll,
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
                    'current_stock' => $globalStock,
                    'fabric_receive' => $detail->receive_qty,
                    'cumulative_return' => $detail->return_details_sum_return_qty ?? 0,
                    'yet_to_issue' => $detail->issue_details_sum_issue_qty,
                    'global_stock' => $globalStock,
                    'mrr_no' => $detail->receive->receive_no,
                    'receive_return_type' => 'manual',
                ];
            });
    }

    public function store(FabricReceiveReturnStrategy $strategy)
    {
        $request = $strategy->getRequest();
        $receiveReturn = $strategy->getReceiveReturnModel();

        $receiveDetail = $receiveReturn->details()->findOrNew($request->id ?? null);
        $receiveDetail->fill($request->all())->save();

        return $receiveDetail;
    }
}
