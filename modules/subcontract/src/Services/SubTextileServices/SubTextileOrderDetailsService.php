<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrderDetail;

class SubTextileOrderDetailsService
{
    public static function formatForReceive($orderId, $greyStoreReceive): array
    {
        return SubTextileOrderDetail::query()->where('sub_textile_order_id', $orderId)
            ->get()->map(function ($collection) use ($greyStoreReceive) {
                return [
                    'sub_textile_operation_id' => $collection->sub_textile_operation_id,
                    'sub_grey_store_receive_id' => $greyStoreReceive->id,
                    'factory_id' => $collection->factory_id,
                    'body_part_id' => $collection->body_part_id,
                    'supplier_id' => $collection->supplier_id,
                    'sub_textile_order_id' => $collection->sub_textile_order_id,
                    'sub_textile_order_detail_id' => $collection->id,
                    'fabric_composition_id' => $collection->fabric_composition_id,
                    'fabric_type_id' => $collection->fabric_type_id,
                    'color_id' => $collection->color_id,
                    'ld_no' => $collection->ld_no,
                    'color_type_id' => $collection->color_type_id,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'gsm' => $collection->gsm,
                    'fabric_description' => $collection->fabric_description,
                    'yarn_details' => $collection->yarn_details,
                    'unit_of_measurement_id' => $collection->unit_of_measurement_id,
                    'sub_grey_store_id' => $greyStoreReceive->sub_grey_store_id,
                    'challan_no' => $greyStoreReceive->challan_no,
                    'challan_date' => $greyStoreReceive->challan_date,
                    'grey_required_qty' => $collection->order_qty,
                    'total_roll' => 0,
                    'receive_qty' => null,
                    'return_roll' => null,
                    'receive_return_qty' => null,
                    'remarks' => null,
                ];
            })->toArray();
    }

    public static function formatForIssue($orderId, $greyStoreIssue): array
    {
        return SubTextileOrderDetail::query()->where('sub_textile_order_id', $orderId)
            ->get()->map(function ($collection) use ($greyStoreIssue) {
                return [
                    'factory_id' => $collection->factory_id,
                    'body_part_id' => $collection->body_part_id,
                    'sub_grey_store_issue_id' => $greyStoreIssue->id,
                    'supplier_id' => $collection->supplier_id,
                    'sub_textile_order_id' => $collection->sub_textile_order_id,
                    'sub_textile_order_detail_id' => $collection->id,
                    'fabric_composition_id' => $collection->fabric_composition_id,
                    'fabric_type_id' => $collection->fabric_type_id,
                    'color_id' => $collection->color_id,
                    'ld_no' => $collection->ld_no,
                    'color_type_id' => $collection->color_type_id,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'gsm' => $collection->gsm,
                    'fabric_description' => $collection->fabric_description,
                    'yarn_details' => $collection->yarn_details,
                    'unit_of_measurement_id' => $collection->unit_of_measurement_id,
                    'sub_grey_store_id' => $greyStoreIssue->sub_grey_store_id,
                    'sub_dyeing_unit_id' => $greyStoreIssue->sub_dyeing_unit_id,
                    'challan_no' => $greyStoreIssue->challan_no,
                    'challan_date' => $greyStoreIssue->challan_date,
                    'sub_textile_operation_id' => $collection->sub_textile_operation_id,
                    'sub_textile_process_id' => $collection->sub_textile_process_id,
                    'grey_required_qty' => null,
                    'total_roll' => 0,
                    'issue_qty' => null,
                    'return_roll' => null,
                    'issue_return_qty' => null,
                    'total_batch_assigned_qty' => null,
                    'remarks' => null,
                ];
            })->toArray();
    }
}
