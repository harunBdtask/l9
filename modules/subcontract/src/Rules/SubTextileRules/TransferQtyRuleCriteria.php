<?php

namespace SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreStockSummaryReport;

class TransferQtyRuleCriteria
{
    public function getCriteria(): array
    {
        return [
            'sub_textile_operation_id' => request()->input('form_operation_id'),
            'body_part_id' => request()->input('form_body_part_id'),
            'fabric_composition_id' => request()->input('form_fabric_composition_id'),
            'fabric_type_id' => request()->input('form_fabric_type_id'),
            'color_id' => request()->input('form_color_id'),
            'ld_no' => request()->input('form_ld_no'),
            'color_type_id' => request()->input('form_color_type_id'),
            'finish_dia' => request()->input('form_finish_dia'),
            'dia_type_id' => request()->input('form_dia_type_id'),
            'gsm' => request()->input('form_gsm'),
            'unit_of_measurement_id' => request()->input('form_unit_of_measurement_id'),
        ];
    }

    public function getStockSummary()
    {
        return SubGreyStoreStockSummaryReport::query()->where($this->getCriteria())->first();
    }
}
