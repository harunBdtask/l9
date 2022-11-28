<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices\States\BatchDetailsStates;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Subcontract\Filters\Filter;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatchDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;

class ReceiveBasis implements BatchDetailsContract
{
    public function format(Request $request)
    {
        return SubGreyStoreReceiveDetails::query()
            ->with([
                'subDyeingOrderDetail',
                'subDyeingOrder',
                'color',
            ])
            ->where('factory_id', $request->query('factory_id'))
            ->where('supplier_id', $request->query('supplier_id'))
            ->when(
                $request->has('sub_textile_order_id'),
                Filter::applyFilter('sub_textile_order_id', $request->get('sub_textile_order_id'))
            )
            ->get()
            ->map(function ($collection) {
                $criteria = [
                    'material_description' => $collection->fabric_description,
                    'color_id' => $collection->color_id,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'sub_textile_order_id' => $collection->sub_textile_order_id,
                    'supplier_id' => $collection->supplier_id,
                ];

                $totalReceiveQty = SubDyeingBatchDetail::query()->where($criteria)->sum('batch_weight');

                return [
                    'factory_id' => $collection->factory_id,
                    'sub_dyeing_batch_id' => null,
                    'supplier_id' => $collection->supplier_id,
                    'sub_textile_order_id' => $collection->sub_textile_order_id,
                    'sub_textile_order_no' => $collection->subDyeingOrder->order_no,
                    'sub_textile_order_detail_id' => $collection->sub_textile_order_detail_id,
                    'sub_grey_store_id' => $collection->sub_grey_store_id,
                    'sub_dyeing_unit_id' => 1,
                    'sub_textile_operation_id' => $collection->sub_textile_operation_id,
                    'sub_textile_process_id' => $collection->subDyeingOrderDetail->sub_textile_process_id,
                    'fabric_composition_id' => $collection->fabric_composition_id,
                    'fabric_type_id' => $collection->fabric_type_id,
                    'color_id' => $collection->color_id,
                    'color_name' => $collection->color->name,
                    'ld_no' => $collection->ld_no,
                    'color_type_id' => $collection->color_type_id,
                    'finish_dia' => $collection->finish_dia,
                    'dia_type_id' => $collection->dia_type_id,
                    'dia_type_value' => $collection->dia_type_value['name'],
                    'gsm' => $collection->gsm,
                    'material_description' => $collection->fabric_description,
                    'fabric_description' => $collection->fabric_description,
                    'yarn_details' => $collection->yarn_details,
                    'grey_required_qty' => $collection->grey_required_qty,
                    'unit_of_measurement_id' => $collection->unit_of_measurement_id,
                    'stitch_length' => null,
                    'batch_roll' => $collection->total_roll,
                    'issue_qty' => $collection->receive_qty,
                    'batch_weight' => $collection->receive_qty,
                    'total_receive_qty' => $totalReceiveQty,
                    'balance' => $totalReceiveQty - $collection->receive_qty,
                    'remarks' => null,
                ];
            })->reject(function ($collection) {
                return $collection['issue_qty'] === null;
            })->values();
    }
}
