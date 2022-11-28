<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class StyleBasisSearchFormatter implements DetailsFormatter
{

    public static function format($collections, $yarnActionStatus = null): Collection
    {
        $uomKg = UnitOfMeasurement::query()->where('unit_of_measurement', 'LIKE', '%kg%')->first()['id'];
        $formattedData = [];
        foreach ($collections as $collection) {
            $formattedData[] = [
                'buyer_name' => $collection->buyer->name,
                'buyer_id' => $collection->buyer_id,
                'style_name' => $collection->style_name,
                'unique_id' => $collection->job_no,
                'is_approve' => $collection->is_approve,
                'yarnActionStatus' => $yarnActionStatus,
                'style_year' => Carbon::make($collection->created_at)->format("d-m-Y"),
                'details' => collect($collection['fabricCosting']
                    ? $collection['fabricCosting']['details']['details']['yarnCostForm']
                    : [])
                    ->map(function ($value, $key) use ($collection, $uomKg) {
                        $cons_total_qty = collect($collection['fabricCosting']['details']['details']['fabricForm'])
                                ->where('grey_cons_total_quantity', '!=', null)
                            ->first()['grey_cons_total_quantity'] ?? 0;
                        $total_wo_qty = $cons_total_qty * 0.01 * $value['percentage'];

                        /* Requirement By Mahfuz Bhai */
                        $woQty = $collection['fabricCosting']['details']['details']['fabricForm'][$key]['grey_cons_total_quantity'] ?? 0;
                        $woQtyPercentage = ($cons_total_qty * $value['percentage']) / 100;
                        $totalWOQtyPercentage = (($woQtyPercentage * $value['process_loss']) / 100) + $woQtyPercentage;

                        return [
                            'id' => null,
                            'requisition_id' => null,
                            'requisition_no' => null,
                            'unique_id' => $collection->job_no,
                            'buyer' => $collection->buyer->name,
                            'buyer_id' => $collection->buyer_id,
                            'budget_id' => $collection->id,
                            'requisition_details_id' => null,
                            'factory_id' => $collection->factory_id,
                            'style_name' => $collection->style_name,
                            'fabric_description' => $value['fabric_description'] ?? null,
                            'fabric_composition_id' => $value['fabric_composition_id'] ?? null,
                            'yarn_count' => $value['count_value'] ?? null,
                            'yarn_count_id' => $value['count'] ?? null,
                            'yarn_type_id' => null,
                            'yarn_type' => $value['type'] ?? null,
                            'uom' => null,
                            'uom_id' => $uomKg,
                            'yarn_color' => $value['color'] ?? null,
                            'yarn_composition' => $value['yarn_composition_value'] ?? null,
                            'yarn_composition_id' => $value['yarn_composition'] ?? null,
                            'percentage' => $value['percentage'] ?? null,
                            'requisition_qty' => $total_wo_qty,
                            'wo_qty' => $woQtyPercentage, //$total_wo_qty,
                            'process_loss' => $value['process_loss'],
                            'total_work_order_qty' => $totalWOQtyPercentage,
                            'rate' => $value['rate'] ?? null,
                            'amount' => $woQty * $value['rate'], //$value['amount'] ?? null,
                            'total_amount' => $totalWOQtyPercentage * $value['rate'], //$value['amount'] ?? null,
                            'delivery_start_date' => null,
                            'delivery_end_date' => null,
                            'remarks' => null,
                        ];
                    }),
            ];
        }

        return collect($formattedData)->where('details', '!=', '[]');
    }
}
