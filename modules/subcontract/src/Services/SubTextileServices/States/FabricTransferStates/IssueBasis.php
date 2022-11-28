<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\FabricTransferStates;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssueDetail;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\SubGreyStoreCriteriaService;

class IssueBasis implements FabricTransferContract
{
    public function handle(Request $request)
    {
        $storeId = $request->get('store_id');
        $orderId = $request->query('order_id');

        $issueDetails = SubGreyStoreIssueDetail::query()
            ->where('sub_grey_store_id', $storeId)
            ->where('sub_textile_order_id', $orderId)
            ->groupBy('sub_textile_order_detail_id')
            ->get()
            ->map(function ($detail) {
                $stockSummary = (new SubGreyStoreCriteriaService())
                    ->setDetail($detail)
                    ->getStockSummary();

                $actualReceiveQty = $stockSummary->receive_qty - $stockSummary->receive_return_qty;
                $actualIssueQty = $stockSummary->issue_qty - $stockSummary->issue_return_qty;
                $balance = $actualReceiveQty - $actualIssueQty;

                return [
                    'from_order_detail_id' => $detail->sub_textile_order_detail_id,
                    'to_order_detail_id' => $detail->sub_textile_order_detail_id,
                    'operation' => $detail->operation->name,
                    'body_part' => $detail->bodyPart->name,
                    'fabric_composition' => $detail->fabric_composition_value,
                    'fabric_type' => $detail->fabricType->construction_name,
                    'color_id' => $detail->color_id,
                    'color' => $detail->color->name,
                    'color_type' => $detail->colorType->color_types,
                    'ld_no' => $detail->ld_no,
                    'finish_dia' => $detail->finish_dia,
                    'dia_type' => $detail->dia_type_value['name'],
                    'gsm' => $detail->gsm,
                    'grey_req_qty' => $detail->grey_required_qty,
                    'grey_available_stock_qty' => $balance,
                    'total_roll' => $detail->total_roll,
                    'total_issue_qty' => $detail->issue_qty,
                    'uom' => $detail->unitOfMeasurement->unit_of_measurement,
                ];
            });

        $colors = collect($issueDetails)->map(function ($color) {
            return [
                'id' => $color['from_order_detail_id'],
                'text' => $color['color'],
            ];
        });

        return [
            'colors' => $colors,
            'details' => $issueDetails,
        ];
    }
}
