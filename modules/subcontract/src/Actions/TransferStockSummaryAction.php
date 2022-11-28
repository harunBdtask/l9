<?php

namespace SkylarkSoft\GoRMG\Subcontract\Actions;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreDailyStockSummaryReport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreFabricTransferDetailMSI;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreStockSummaryReport;

class TransferStockSummaryAction
{
    /**
     * @param $fabricDetails
     * @param bool $date
     * @return array
     */
    public function setFromCriteria($fabricDetails, bool $date = false): array
    {
        $criteria = [
            'sub_textile_operation_id' => $fabricDetails->detailMSI->form_operation_id,
            'body_part_id' => $fabricDetails->detailMSI->form_body_part_id,
            'fabric_composition_id' => $fabricDetails->detailMSI->form_fabric_composition_id,
            'fabric_type_id' => $fabricDetails->detailMSI->form_fabric_type_id,
            'color_id' => $fabricDetails->detailMSI->form_color_id,
            'ld_no' => $fabricDetails->detailMSI->form_ld_no,
            'color_type_id' => $fabricDetails->detailMSI->form_color_type_id,
            'finish_dia' => $fabricDetails->detailMSI->form_finish_dia,
            'dia_type_id' => $fabricDetails->detailMSI->form_dia_type_id,
            'gsm' => $fabricDetails->detailMSI->form_gsm,
            'unit_of_measurement_id' => $fabricDetails->detailMSI->form_unit_of_measurement_id,
        ];

        if ($date) {
            $criteria['production_date'] = $fabricDetails->transfer_date;
        }

        return $criteria;
    }

    /**
     * @param $fabricDetails
     * @param bool $date
     * @return array
     */
    public function setToCriteria($fabricDetails, bool $date = false): array
    {
        $criteria = [
            'sub_textile_operation_id' => $fabricDetails->detailMSI->to_operation_id,
            'body_part_id' => $fabricDetails->detailMSI->to_body_part_id,
            'fabric_composition_id' => $fabricDetails->detailMSI->to_fabric_composition_id,
            'fabric_type_id' => $fabricDetails->detailMSI->to_fabric_type_id,
            'color_id' => $fabricDetails->detailMSI->to_color_id,
            'ld_no' => $fabricDetails->detailMSI->to_ld_no,
            'color_type_id' => $fabricDetails->detailMSI->to_color_type_id,
            'finish_dia' => $fabricDetails->detailMSI->to_finish_dia,
            'dia_type_id' => $fabricDetails->detailMSI->to_dia_type_id,
            'gsm' => $fabricDetails->detailMSI->to_gsm,
            'unit_of_measurement_id' => $fabricDetails->detailMSI->to_unit_of_measurement_id,
        ];

        if ($date) {
            $criteria['production_date'] = $fabricDetails->transfer_date;
        }

        return $criteria;
    }

    /**
     * @param $fabricDetails
     * @return void
     */
    public function attachToStockSummaryReport($fabricDetails)
    {
        $fromStockSummary = SubGreyStoreStockSummaryReport::query()
            ->where($this->setFromCriteria($fabricDetails))
            ->first();

        $toStockSummary = SubGreyStoreStockSummaryReport::query()
            ->where($this->setToCriteria($fabricDetails))
            ->first();

        $fromStockSummary->update([
            'transfer_qty' => $this->computeFromTransferQty($fabricDetails),
        ]);

        $toStockSummary->update([
            'receive_transfer_qty' => $this->computeToTransferQty($fabricDetails),
        ]);
    }

    /**
     * @param $fabricDetails
     * @return void
     */
    public function attachToDailyStockSummaryReport($fabricDetails)
    {
        $fromAttributes = array_merge($this->setFromCriteria($fabricDetails, true), [
            'factory_id' => $fabricDetails->transfer->from_company,
            'supplier_id' => $fabricDetails->from_supplier_id,
            'sub_grey_store_id' => $fabricDetails->from_store_id,
            'material_description' => $fabricDetails->detailMSI->form_fabric_description,
        ]);

        $fromStockSummary = SubGreyStoreDailyStockSummaryReport::query()
            ->updateOrCreate($this->setFromCriteria($fabricDetails, true), $fromAttributes);

        $fromStockSummary->update([
            'transfer_qty' => $this->computeFromTransferQty($fabricDetails, true),
        ]);

        $toAttributes = array_merge($this->setToCriteria($fabricDetails, true), [
            'factory_id' => $fabricDetails->transfer->to_company,
            'supplier_id' => $fabricDetails->to_supplier_id,
            'sub_grey_store_id' => $fabricDetails->to_store_id,
            'material_description' => $fabricDetails->detailMSI->to_fabric_description,
        ]);

        $toStockSummary = SubGreyStoreDailyStockSummaryReport::query()
            ->updateOrCreate($this->setToCriteria($fabricDetails, true), $toAttributes);

        $toStockSummary->update([
            'receive_transfer_qty' => $this->computeToTransferQty($fabricDetails, true),
        ]);
    }

    /**
     * @param $fabricDetails
     * @param bool $date
     * @return int|mixed
     */
    private function computeFromTransferQty($fabricDetails, bool $date = false)
    {
        $criteria = [
            'form_operation_id' => $fabricDetails->detailMSI->form_operation_id,
            'form_body_part_id' => $fabricDetails->detailMSI->form_body_part_id,
            'form_fabric_composition_id' => $fabricDetails->detailMSI->form_fabric_composition_id,
            'form_fabric_type_id' => $fabricDetails->detailMSI->form_fabric_type_id,
            'form_color_id' => $fabricDetails->detailMSI->form_color_id,
            'form_ld_no' => $fabricDetails->detailMSI->form_ld_no,
            'form_color_type_id' => $fabricDetails->detailMSI->form_color_type_id,
            'form_finish_dia' => $fabricDetails->detailMSI->form_finish_dia,
            'form_dia_type_id' => $fabricDetails->detailMSI->form_dia_type_id,
            'form_gsm' => $fabricDetails->detailMSI->form_gsm,
            'form_unit_of_measurement_id' => $fabricDetails->detailMSI->form_unit_of_measurement_id,
            'form_fabric_description' => $fabricDetails->detailMSI->form_fabric_description,
        ];

        if ($date) {
            $criteria['transfer_date'] = $fabricDetails->detailMSI->transfer_date;
            ;
        }

        return SubGreyStoreFabricTransferDetailMSI::query()
            ->where($criteria)->sum('transfer_qty');
    }

    /**
     * @param $fabricDetails
     * @param bool $date
     * @return int|mixed
     */
    private function computeToTransferQty($fabricDetails, bool $date = false)
    {
        $criteria = [
            'to_operation_id' => $fabricDetails->detailMSI->to_operation_id,
            'to_body_part_id' => $fabricDetails->detailMSI->to_body_part_id,
            'to_fabric_composition_id' => $fabricDetails->detailMSI->to_fabric_composition_id,
            'to_fabric_type_id' => $fabricDetails->detailMSI->to_fabric_type_id,
            'to_color_id' => $fabricDetails->detailMSI->to_color_id,
            'to_ld_no' => $fabricDetails->detailMSI->to_ld_no,
            'to_color_type_id' => $fabricDetails->detailMSI->to_color_type_id,
            'to_finish_dia' => $fabricDetails->detailMSI->to_finish_dia,
            'to_dia_type_id' => $fabricDetails->detailMSI->to_dia_type_id,
            'to_gsm' => $fabricDetails->detailMSI->to_gsm,
            'to_unit_of_measurement_id' => $fabricDetails->detailMSI->to_unit_of_measurement_id,
            'to_fabric_description' => $fabricDetails->detailMSI->to_fabric_description,
        ];

        if ($date) {
            $criteria['transfer_date'] = $fabricDetails->detailMSI->transfer_date;
        }

        return SubGreyStoreFabricTransferDetailMSI::query()
            ->where($criteria)->sum('transfer_qty');
    }
}
