<?php

namespace SkylarkSoft\GoRMG\Subcontract\Rules\SubTextileRules;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreStockSummaryReport;

class QtyRuleCriteria
{
    public function getCriteria(): array
    {
        return [
            'sub_textile_operation_id' => request()->input('sub_textile_operation_id'),
            'body_part_id' => request()->input('body_part_id'),
            'fabric_composition_id' => request()->input('fabric_composition_id'),
            'fabric_type_id' => request()->input('fabric_type_id'),
            'color_id' => request()->input('color_id'),
            'ld_no' => request()->input('ld_no'),
            'color_type_id' => request()->input('color_type_id'),
            'finish_dia' => request()->input('finish_dia'),
            'dia_type_id' => request()->input('dia_type_id'),
            'gsm' => request()->input('gsm'),
            'unit_of_measurement_id' => request()->input('unit_of_measurement_id'),
        ];
    }

    public function getStockSummary()
    {
        return SubGreyStoreStockSummaryReport::query()->where($this->getCriteria())->first();
    }
}
