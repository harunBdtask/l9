<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\FabricStore\FabricIssue;

use Exception;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\BalanceQty;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricBarcodeDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;

class FabricBarcodeIssue implements FabricIssueContracts
{
    /**
     * @throws Exception
     */
    public function handle(FabricIssueStrategy $strategy): array
    {
        $request = $strategy->getRequest();
        $code = $request->get('code');

        $detail = FabricBarcodeDetail::query()->where('code', $code)->first();

        if (!isset($detail)) {
            throw new Exception('Barcode not found');
        }

        if ($detail->status == 1) {
            throw new Exception('Barcode already scan');
        }

        $previousIssueQty = $detail->issueDetails->sum('issue_qty');

        $bookingDetails = FabricBookingDetailsBreakdown::query()
            ->where('job_no', $detail->unique_id)
            ->first();

        $balanceQty = (new BalanceQty)->balance($detail);

        $purchaseOrders = PurchaseOrder::query()
            ->whereIn('po_no', explode(',', $detail->po_no))
            ->orderBy('ex_factory_date');

        return [
            'id' => null,
            'fabric_receive_id' => $detail->fabric_receive_id,
            'fabric_barcode_detail_id' => $detail->id,
            'fabric_receive_details_id' => $detail->fabric_receive_detail_id,
            'code' => $detail->code,
            'store_id' => $detail->receive->store_id,
            'prod_id' => null,
            'style_id' => $detail->style_id,
            'style_name' => $detail->style_name,
            'po_no' => $detail->po_no,
            'construction' => $detail->construction,
            'unique_id' => $detail->unique_id,
            'batch_no' => $detail->batch_no,
            'fabric_color_id' => $detail->color_id,
            'fabric_color_name' => $detail->color->name,
            'fabric_shade' => $detail->fabric_shade,
            'fabric_description' => $detail->fabric_description,
            'dia' => $detail->dia,
            'ac_dia' => $detail->ac_dia,
            'gsm' => $detail->gsm,
            'ac_gsm' => $detail->ac_gsm,
            'dia_type' => $detail->dia_type,
            'ac_dia_type' => $detail->ac_dia_type,
            'color_id' => $detail->color_id,
            'color' => $detail->fabricColor->name,
            'sample_type' => 'Sample 1',
            'uom_id' => $detail->uom_id,
            'uom_name' => $detail->uom->unit_of_measurement,
            'floor_id' => $detail->floor_id,
            'floor_name' => $detail->floor->name,
            'room_id' => $detail->room_id,
            'room_name' => $detail->room->name,
            'rack_id' => $detail->rack_id,
            'rack_name' => $detail->rack->name,
            'shelf_id' => $detail->shelf_id,
            'shelf_name' => $detail->shelf->name,
            'receive_qty' => $detail->qty,
            'previous_issue_qty' => $previousIssueQty,
            'issue_qty' => $detail->qty,
            'amount' => $detail->amount,
            'balance_qty' => $balanceQty,
            'gmts_item_id' => $detail->gmts_item_id,
            'gmts_item_name' => $detail->item->name,
            'body_part_id' => $detail->body_part_id,
            'body_part_value' => $bookingDetails->body_part_value ?? $detail->body->name,
            'rate' => $detail->rate,
            'no_of_roll' => $detail->receiveDetail->no_of_roll,
            'cutting_unit_no' => null,
            'remarks' => null,
            'fabric_composition_id' => $detail->fabric_composition_id,
            'color_type_id' => $detail->color_type_id,
            'issue_type' => 'barcode',
            'issue_qty_details' => [
                [
                    'po_no' => $detail->po_no,
                    'file_no' => null,
                    'ref_no' => null,
                    'shipment_date' => $purchaseOrders->first()['ex_factory_date'] ?? null,
                    'po_qty' => $purchaseOrders->sum('po_quantity') ?? '0.000',
                    'req_qty' => null,
                    'receive_qty' => $detail->qty,
                    'balance_qty' => $balanceQty,
                    'cumu_issue_qty' => $previousIssueQty,
                    'no_of_roll' => $detail->receiveDetail->no_of_roll,
                    'issue_qty' => $detail->qty,
                ]
            ],
        ];
    }

    public function store(FabricIssueStrategy $strategy)
    {
        $request = $strategy->getRequest();
        $fabricIssue = $strategy->getIssueModel();

        $fabricBarcodeDetail = FabricBarcodeDetail::query()->findOrFail($request->get('fabric_barcode_detail_id'));
        $fabricBarcodeDetail->update(['status' => FabricBarcodeDetail::USED]);

        $fabricIssueDetail = $fabricIssue->details()->findOrNew($request->id ?? null);
        $request['issue_date'] = $fabricIssue->issue_date;
        $fabricIssueDetail->fill($request->all())->save();

        return $fabricIssueDetail;
    }
}
