<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder;

use Illuminate\Support\Collection;

class IndependentBasisEditFormatter implements DetailsFormatter
{

    public static function format($collections): Collection
    {
        return collect($collections->details)->map(function ($value) {
            return [
                'id' => $value->id ?? null,
                'requisition_id' => null,
                'requisition_no' => null,
                'unique_id' => $value->unique_id,
                'buyer' => $value->buyer->name,
                'buyer_id' => $value->buyer_id,
                'budget_id' => null,
                'requisition_details_id' => null,
                'factory_id' => $value->factory_id,
                'style_name' => $value->style_name,
                'yarn_count' => $value->yarnCount->yarn_count ?? null,
                'yarn_count_id' => $value->yarn_count_id ?? null,
                'yarn_type_id' => $value->yarn_type_id,
                'yarn_type' => $value->yarn_type ?? $value->yarnType->yarn_type,
                'uom' => $value->unitOfMeasurement->unit_of_measurement,
                'uom_id' => $value->uom_id,
                'yarn_color' => $value->yarn_color ?? null,
                'yarn_composition' => $value->yarnComposition->yarn_composition ?? null,
                'yarn_composition_id' => $value->yarn_composition_id ?? null,
                'fabric_description' => $value->fabric_description,
                'fabric_composition_id' => $value->fabric_composition_id,
                'percentage' => $value->percentage ?? null,
                'requisition_qty' => null,
                'wo_qty' => $value->wo_qty ?? null,
                'rate' => $value->rate ?? null,
                'amount' => $value->amount ?? null,
                'delivery_start_date' => $value->delivery_start_date ?? null,
                'delivery_end_date' => $value->delivery_end_date ?? null,
                'remarks' => $value->remarks ?? null,
                'process_loss' => $value->process_loss ?? null,
                'total_amount' => $value->total_amount ?? null,
                'total_work_order_qty' => (($value->wo_qty * $value->process_loss) / 100) + $value->wo_qty,
            ];
        });
    }
}
