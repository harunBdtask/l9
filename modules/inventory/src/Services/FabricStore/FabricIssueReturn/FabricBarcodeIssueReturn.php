<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricIssueReturn;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssue;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\BalanceQty;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricBarcodeDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueReturnDetail;

class FabricBarcodeIssueReturn implements FabricIssueReturnContracts
{
    /**
     * @throws Exception
     */
    public function handle(FabricIssueReturnStrategy $strategy): array
    {
        $request = $strategy->getRequest();
        $code = $request->get('code');

        $detail = FabricIssueDetail::query()->with('issue')->whereHas('barcodeDetail', function (Builder $query) use ($code) {
            $query->where('code', $code);
        })->orderBy('id', 'desc')->first();

        if (!isset($detail)) {
            throw new Exception('Barcode not found');
        }

        if ($detail->issue->status != FabricIssue::APPROVE) {
            throw new Exception('Issue not approved');
        }

        if ($detail->barcodeDetail->status == 0) {
            throw new Exception('Barcode already returned');
        }

        $previousIssueReturnQty = FabricIssueReturnDetail::query()
                                      ->where('unique_id', $detail->unique_id)
                                      ->first()['return_qty'] ?? 0;

        $balanceQty = (new BalanceQty)->balance($detail);

        return [
            'unique_id' => $detail->unique_id,
            'fabric_issue_detail_id' => $detail->id,
            'fabric_barcode_detail_id' => $detail->fabric_barcode_detail_id,
            'issue_no' => $detail->issue->issue_no,
            'buyer_id' => $detail->buyer_id,
            'style_id' => $detail->style_id,
            'style_name' => $detail->style_name,
            'po_no' => $detail->po_no,
            'batch_no' => $detail->batch_no,
            'gmts_item_id' => $detail->gmts_item_id,
            'gmts_item_name' => $detail->gmtsItem->name,
            'body_part_id' => $detail->body_part_id,
            'body_part_value' => $detail->bookingDetail->body_part_value,
            'fabric_description' => $detail->fabric_description,
            'fabric_composition_id' => $detail->fabric_composition_id,
            'construction' => $detail->construction,
            'dia' => $detail->dia,
            'ac_dia' => $detail->ac_dia,
            'gsm' => $detail->gsm,
            'ac_gsm' => $detail->ac_gsm,
            'dia_type' => $detail->dia_type,
            'ac_dia_type' => $detail->ac_dia_type,
            'color_id' => $detail->color_id,
            'color_name' => $detail->color->name,
            'color_type_id' => $detail->color_type_id,
            'contrast_color_id' => $detail->contrast_color_id,
            'contrast_color_name' => null,
            'uom_id' => $detail->uom_id,
            'uom_name' => $detail->uom->unit_of_measurement,
            'reject_qty' => $detail->reject_qty,
            'return_qty' => $detail->issue_qty,
            'rate' => $detail->rate,
            'amount' => number_format($detail->issue_qty * $detail->rate, 4),
            'fabric_shade' => $detail->fabric_shade,
            'no_of_roll' => $detail->no_of_roll,
            'store_id' => $detail->store_id,
            'floor_id' => $detail->floor_id,
            'floor_name' => $detail->floor->name,
            'room_id' => $detail->room_id,
            'room_name' => $detail->room->name,
            'rack_id' => $detail->rack_id,
            'rack_name' => $detail->rack->name,
            'shelf_id' => $detail->shelf_id,
            'shelf_name' => $detail->shelf->name,
            'remarks' => null,
            'issue_qty' => $detail->issue_qty,
            'previous_issue_return_qty' => $previousIssueReturnQty,
            'yet_to_return' => $detail->issue_qty - $previousIssueReturnQty,
            'balance_qty' => $balanceQty,
            'issue_return_type' => 'barcode',
        ];
    }

    public function store(FabricIssueReturnStrategy $strategy)
    {
        $request = $strategy->getRequest();
        $fabricIssueReturn = $strategy->getIssueReturnModel();

        $fabricBarcodeDetail = FabricBarcodeDetail::query()->findOrFail($request->get('fabric_barcode_detail_id'));
        $fabricBarcodeDetail->update(['status' => FabricBarcodeDetail::NOT_USE]);

        $fabricIssueReturnDetail = $fabricIssueReturn->details()->findOrNew($request->id ?? null);
        $fabricIssueReturnDetail->fill($request->all())->save();

        return $fabricIssueReturnDetail;
    }
}
