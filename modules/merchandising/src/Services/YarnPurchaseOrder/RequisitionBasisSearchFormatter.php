<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder;

use Illuminate\Support\Collection;

class RequisitionBasisSearchFormatter implements DetailsFormatter
{

    public static function format($collections, $yarnActionStatus = null): Collection
    {
        $formattedData = [];
        foreach ($collections as $requisition) {
            $formattedDetails = collect($requisition->details)->map(function ($detail) use ($requisition, $yarnActionStatus) {
                return [
                    'id' => null,
                    'requisition_id' => $requisition->id,
                    'requisition_no' => $detail->requisition_no,
                    'unique_id' => $detail->unique_id,
                    'buyer' => $detail->buyer->name,
                    'buyer_id' => $detail->buyer_id,
                    'requisition_details_id' => $detail->id,
                    'factory_id' => $detail->factory_id,
                    'style_name' => $detail->style_name,
                    'is_approve' => $detail->is_approve,
                    'yarnActionStatus' => $yarnActionStatus,
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
                    'wo_qty' => $detail->requisition_qty,
                    'rate' => $detail->rate,
                    'amount' => $detail->amount,
                    'delivery_start_date' => null,
                    'delivery_end_date' => null,
                    'remarks' => null,
                ];
            });

            $formattedData[] = [
                'requisition_date' => $requisition->requisition_date,
                'required_date' => $requisition->required_date,
                'requisition_no' => $requisition->requisition_no,
                'pay_mode_value' => $requisition->pay_mode_value,
                'source_value' => $requisition->source_value,
                'details' => $formattedDetails
            ];
        }
        return collect($formattedData)->where('details', '!=', '[]');
    }
}
