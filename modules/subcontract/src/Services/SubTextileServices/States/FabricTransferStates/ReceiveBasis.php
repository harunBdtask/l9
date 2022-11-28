<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\FabricTransferStates;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;
use SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\SubGreyStoreCriteriaService;

class ReceiveBasis implements FabricTransferContract
{
    public function handle(Request $request): array
    {
        $storeId = $request->query('store_id');
        $orderId = $request->query('order_id');

        $receiveDetails = SubGreyStoreReceiveDetails::query()
            ->with([
                'operation',
                'bodyPart',
                'color',
                'unitOfMeasurement',
                'subDyeingOrderDetail',
            ])
            ->where('sub_grey_store_id', $storeId)
            ->where('sub_textile_order_id', $orderId)
            ->groupBy('sub_textile_order_detail_id')
            ->get()->map(function ($detail) {
                $stockSummary = (new SubGreyStoreCriteriaService())
                    ->setDetail($detail)
                    ->getStockSummary();

                $actualReceiveQty = ($stockSummary->receive_qty + $stockSummary->receive_transfer_qty) -
                    $stockSummary->receive_return_qty - $stockSummary->transfer_qty;

                $actualIssueQty = $stockSummary->issue_qty - $stockSummary->issue_return_qty;

                $balance = $actualReceiveQty - $actualIssueQty;

                return [
                    'from_order_detail_id' => $detail->sub_textile_order_detail_id,
                    'to_order_detail_id' => $detail->sub_textile_order_detail_id,
                    'color' => $detail->color->name,
                    'form' => [
                        'from_supplier_id' => $detail->supplier_id,
                        'form_operation_id' => $detail->sub_textile_operation_id,
                        'form_operation' => $detail->operation->name,
                        'form_body_part_id' => $detail->body_part_id,
                        'form_body_part' => $detail->bodyPart->name,
                        'form_fabric_composition_id' => $detail->fabric_composition_id,
                        'form_fabric_composition' => $detail->fabric_composition_value,
                        'form_fabric_type_id' => $detail->fabric_type_id,
                        'form_fabric_type' => $detail->fabricType->construction_name,
                        'form_color_id' => $detail->color_id,
                        'form_color' => $detail->color->name,
                        'form_color_type_id' => $detail->color_type_id,
                        'form_color_type' => $detail->colorType->color_types,
                        'form_ld_no' => $detail->ld_no,
                        'form_finish_dia' => $detail->finish_dia,
                        'form_dia_type_id' => $detail->dia_type_id,
                        'form_dia_type' => $detail->dia_type_value['name'],
                        'form_fabric_description' => $detail->fabric_description,
                        'form_gsm' => $detail->gsm,
                        'form_unit_of_measurement_id' => $detail->unit_of_measurement_id,
                        'form_uom' => $detail->unitOfMeasurement->unit_of_measurement,
                        'form_total_roll' => $detail->total_roll,
                        'form_grey_req_qty' => $balance,
                        'form_grey_available_stock_qty' => $balance,
                        'from_total_receive_qty' => $balance,
                    ],
                    'to' => [
                        'to_supplier_id' => $detail->supplier_id,
                        'to_operation_id' => $detail->sub_textile_operation_id,
                        'to_operation' => $detail->operation->name,
                        'to_body_part_id' => $detail->body_part_id,
                        'to_body_part' => $detail->bodyPart->name,
                        'to_fabric_composition_id' => $detail->fabric_composition_id,
                        'to_fabric_composition' => $detail->fabric_composition_value,
                        'to_fabric_type_id' => $detail->fabric_type_id,
                        'to_fabric_type' => $detail->fabricType->construction_name,
                        'to_color_id' => $detail->color_id,
                        'to_color' => $detail->color->name,
                        'to_color_type_id' => $detail->color_type_id,
                        'to_color_type' => $detail->colorType->color_types,
                        'to_ld_no' => $detail->ld_no,
                        'to_finish_dia' => $detail->finish_dia,
                        'to_dia_type_id' => $detail->dia_type_id,
                        'to_dia_type' => $detail->dia_type_value['name'],
                        'to_fabric_description' => $detail->fabric_description,
                        'to_gsm' => $detail->gsm,
                        'to_unit_of_measurement_id' => $detail->unit_of_measurement_id,
                        'to_uom' => $detail->unitOfMeasurement->unit_of_measurement,
                        'to_total_roll' => $detail->total_roll,
                        'to_grey_req_qty' => $balance,
                        'to_grey_available_stock_qty' => $balance,
                        'to_total_receive_qty' => $balance,
                    ],
                    'current_stock' => $balance,
                    'rate' => $detail->subDyeingOrderDetail->price_rate,
                    'amount' => '',
                ];
            });

        $colors = collect($receiveDetails)->map(function ($color) {
            return [
                'id' => $color['from_order_detail_id'],
                'text' => $color['color'],
            ];
        });

        return [
            'colors' => $colors,
            'details' => $receiveDetails,
        ];
    }
}
