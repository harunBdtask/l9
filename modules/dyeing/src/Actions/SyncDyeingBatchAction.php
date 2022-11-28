<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;

class SyncDyeingBatchAction
{

    public function syncBatchFromBatchDetails(DyeingBatch $dyeingBatch)
    {
        $dyeingBatch->load('dyeingBatchDetails');

        $orderIdsJson = $dyeingBatch->getRelation('dyeingBatchDetails')
            ->map(function ($collection) {
                return [
                    'textile_order_id' => $collection->textile_order_id,
                    'textile_order_detail_id' => $collection->textile_order_detail_id,
                ];
            });

        $orderIdsJson = collect($orderIdsJson)->unique('textile_order_detail_id');

        $orderIds = collect($orderIdsJson)->pluck('textile_order_id');

        $ordersNo = TextileOrder::query()->whereIn('id', $orderIds)
            ->pluck('unique_id');

        $fabricCompositionId = $dyeingBatch->getRelation('dyeingBatchDetails')
                                   ->first()['fabric_composition_id'] ?? null;

        $fabricTypeId = $dyeingBatch->getRelation('dyeingBatchDetails')
                            ->first()['fabric_type_id'] ?? null;

        $colorId = $dyeingBatch->getRelation('dyeingBatchDetails')
                       ->first()['color_id'] ?? null;

        $ldNo = $dyeingBatch->getRelation('dyeingBatchDetails')
                    ->first()['ld_no'] ?? null;

        $colorTypeId = $dyeingBatch->getRelation('dyeingBatchDetails')
                           ->first()['color_type_id'] ?? null;

        $finisDia = $dyeingBatch->getRelation('dyeingBatchDetails')
                        ->first()['finish_dia'] ?? null;

        $diaTypeId = $dyeingBatch->getRelation('dyeingBatchDetails')
                         ->first()['dia_type_id'] ?? null;

        $gsm = $dyeingBatch->getRelation('dyeingBatchDetails')->first()['gsm'] ?? null;

        $fabricDescription = $dyeingBatch->getRelation('dyeingBatchDetails')
                                 ->first()['fabric_description'] ?? null;

        $totalBatchWeight = $dyeingBatch->dyeingBatchDetails()->sum('batch_weight');

        $fabricColor = $dyeingBatch->dyeingBatchDetails()->first()['color_id'] ?? null;

        $dyeingBatch->update([
            'textile_orders_id' => $orderIdsJson,
            'orders_no' => $ordersNo,
            'fabric_composition_id' => $fabricCompositionId,
            'fabric_type_id' => $fabricTypeId,
            'color_id' => $colorId,
            'ld_no' => $ldNo,
            'color_type_id' => $colorTypeId,
            'finish_dia' => $finisDia,
            'dia_type_id' => $diaTypeId,
            'gsm' => $gsm,
            'fabric_description' => $fabricDescription,
            'total_batch_weight' => $totalBatchWeight,
            'fabric_color_id' => $fabricColor,
        ]);
    }

    public function syncBatchFromMachineAllocation(DyeingBatch $dyeingBatch): void
    {
        $dyeingBatch->load('machineAllocations');

        $totalMachineCapacity = 0;

        $dyeingBatch->getRelation('machineAllocations')
            ->each(function ($collection) use (&$totalMachineCapacity) {
                $totalMachineCapacity += $collection->machine->capacity;
            });

        $dyeingBatch->update([
            'total_machine_capacity' => $totalMachineCapacity
        ]);
    }
}
