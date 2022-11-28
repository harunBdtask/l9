<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreStockSummaryReport;

class SubGreyStoreCriteriaService
{
    private $detail;

    public function setDetail($detail): SubGreyStoreCriteriaService
    {
        $this->detail = $detail;

        return $this;
    }

    public function getCriteria(): array
    {
        return [
            'sub_textile_operation_id' => $this->detail->sub_textile_operation_id,
            'body_part_id' => $this->detail->body_part_id,
            'fabric_composition_id' => $this->detail->fabric_composition_id,
            'fabric_type_id' => $this->detail->fabric_type_id,
            'color_id' => $this->detail->color_id,
            'ld_no' => $this->detail->ld_no,
            'color_type_id' => $this->detail->color_type_id,
            'finish_dia' => $this->detail->finish_dia,
            'dia_type_id' => $this->detail->dia_type_id,
            'gsm' => $this->detail->gsm,
            'unit_of_measurement_id' => $this->detail->unit_of_measurement_id,
        ];
    }

    public function getStockSummary()
    {
        return SubGreyStoreStockSummaryReport::query()->where($this->getCriteria())->first();
    }
}
