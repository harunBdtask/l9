<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder;

use Illuminate\Support\Collection;

class RequisitionBasisEditFormatter implements DetailsFormatter
{

    public static function format($collections): Collection
    {
        return collect($collections->details)->map(function ($detail) {
            return [
                'id' => $detail->id ?? null,
                'requisition_id' => $detail->requisition_id,
                'requisition_no' => $detail->requisition_no,
                'unique_id' => $detail->unique_id,
                'buyer' => $detail->buyer->name,
                'buyer_id' => $detail->buyer_id,
                'requisition_details_id' => $detail->id,
                'factory_id' => $detail->factory_id,
                'style_name' => $detail->style_name,
                'yarn_count' => $detail->yarnCount->yarn_count,
                'yarn_count_id' => $detail->yarn_count,
                'yarn_type_id' => $detail->yarn_type,
                'yarn_type' => $detail->yarnType->yarn_type,
                'uom' => $detail->unitOfMeasurement->unit_of_measurement,
                'uom_id' => $detail->uom,
                'yarn_color' => $detail->yarn_color,
                'yarn_composition' => $detail->yarnComposition->yarn_composition,
                'yarn_composition_id' => $detail->yarn_composition,
                'percentage' => $detail->percentage,
                'requisition_qty' => $detail->requisition_qty,
                'wo_qty' => $detail->wo_qty,
                'rate' => $detail->rate,
                'amount' => $detail->amount,
                'delivery_start_date' => $detail->delivery_start_date ?? null,
                'delivery_end_date' => $detail->delivery_end_date ?? null,
                'remarks' => $detail->remarks ?? null,
            ];
        });
    }
}
