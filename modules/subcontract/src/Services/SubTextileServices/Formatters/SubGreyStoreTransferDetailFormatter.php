<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\Formatters;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreStockSummaryReport;

class SubGreyStoreTransferDetailFormatter
{
    public function format($transferDetail)
    {
        $fromDetail = $transferDetail->fromOrderDetail;
        $toDetail = $transferDetail->toOrderDetail;

        $formStockSummary = SubGreyStoreStockSummaryReport::query()
            ->where([
                'sub_textile_operation_id' => $transferDetail->detailMSI->form_operation_id,
                'body_part_id' => $transferDetail->detailMSI->form_body_part_id,
                'fabric_composition_id' => $transferDetail->detailMSI->form_fabric_composition_id,
                'fabric_type_id' => $transferDetail->detailMSI->form_fabric_type_id,
                'color_id' => $transferDetail->detailMSI->form_color_id,
                'ld_no' => $transferDetail->detailMSI->form_ld_no,
                'color_type_id' => $transferDetail->detailMSI->form_color_type_id,
                'finish_dia' => $transferDetail->detailMSI->form_finish_dia,
                'dia_type_id' => $transferDetail->detailMSI->form_dia_type_id,
                'gsm' => $transferDetail->detailMSI->form_gsm,
                'unit_of_measurement_id' => $transferDetail->detailMSI->form_unit_of_measurement_id,
            ])->first();

        $formActualReceiveQty = ($formStockSummary->receive_qty + $formStockSummary->receive_transfer_qty) -
            $formStockSummary->receive_return_qty - $formStockSummary->transfer_qty;

        $formActualIssueQty = $formStockSummary->issue_qty - $formStockSummary->issue_return_qty;

        $formBalance = $formActualReceiveQty - $formActualIssueQty;

        $toStockSummary = SubGreyStoreStockSummaryReport::query()
            ->where([
                'sub_textile_operation_id' => $transferDetail->detailMSI->to_operation_id,
                'body_part_id' => $transferDetail->detailMSI->to_body_part_id,
                'fabric_composition_id' => $transferDetail->detailMSI->to_fabric_composition_id,
                'fabric_type_id' => $transferDetail->detailMSI->to_fabric_type_id,
                'color_id' => $transferDetail->detailMSI->to_color_id,
                'ld_no' => $transferDetail->detailMSI->to_ld_no,
                'color_type_id' => $transferDetail->detailMSI->to_color_type_id,
                'finish_dia' => $transferDetail->detailMSI->to_finish_dia,
                'dia_type_id' => $transferDetail->detailMSI->to_dia_type_id,
                'gsm' => $transferDetail->detailMSI->to_gsm,
                'unit_of_measurement_id' => $transferDetail->detailMSI->to_unit_of_measurement_id,
            ])->first();

        $toActualReceiveQty = ($toStockSummary->receive_qty + $toStockSummary->receive_transfer_qty) -
            $toStockSummary->receive_return_qty - $toStockSummary->transfer_qty;

        $toActualIssueQty = $toStockSummary->issue_qty - $toStockSummary->issue_return_qty;

        $toBalance = $toActualReceiveQty - $toActualIssueQty;

        return array_merge($transferDetail->toArray(), [
            'color' => $fromDetail->color->name,
            'form_order_no' => $transferDetail->fromOrder->order_no,
            'form_operation' => $fromDetail->subTextileOperation->name,
            'form_body_part' => $fromDetail->bodyPart->name,
            'form_fabric_composition' => $fromDetail->fabric_composition_value,
            'form_fabric_type' => $fromDetail->fabricType->construction_name,
            'form_color_id' => $fromDetail->color_id,
            'form_color' => $fromDetail->color->name,
            'form_color_type' => $fromDetail->colorType->color_types,
            'form_ld_no' => $fromDetail->ld_no,
            'form_finish_dia' => $fromDetail->finish_dia,
            'form_dia_type' => $fromDetail->dia_type_value,
            'form_gsm' => $fromDetail->gsm,
            'form_grey_req_qty' => $formBalance,
            'form_grey_available_stock_qty' => $formBalance,
            'form_total_roll' => $fromDetail->subGreyStoreReceiveDetail[0]->total_roll,
            'from_total_receive_qty' => $formBalance,
            'form_uom' => $fromDetail->unitOfMeasurement->unit_of_measurement,
            'to_order_no' => $transferDetail->toOrder->order_no,
            'to_operation' => $toDetail->subTextileOperation->name,
            'to_body_part' => $toDetail->bodyPart->name,
            'to_fabric_composition' => $toDetail->fabric_composition_value,
            'to_fabric_type' => $toDetail->fabricType->construction_name,
            'to_color_id' => $toDetail->color_id,
            'to_color' => $toDetail->color->name,
            'to_color_type' => $toDetail->colorType->color_types,
            'to_ld_no' => $toDetail->ld_no,
            'to_finish_dia' => $toDetail->finish_dia,
            'to_dia_type' => $toDetail->dia_type_value,
            'to_gsm' => $toDetail->gsm,
            'to_grey_req_qty' => $toBalance,
            'to_grey_available_stock_qty' => $toBalance,
            'to_total_roll' => $toDetail->subGreyStoreReceiveDetail[0]->total_roll,
            'to_total_receive_qty' => $toBalance,
            'to_uom' => $toDetail->unitOfMeasurement->unit_of_measurement,
            'total_receive_qty' => $toDetail->receive_qty,
            'total_receive_transfer_qty' => $toBalance,
            'current_stock' => $toBalance,
            'rate' => $toDetail->price_rate,
            'amount' => $transferDetail->transfer_qty * $toDetail->price_rate,
        ]);
    }
}
