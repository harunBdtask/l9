<?php

namespace SkylarkSoft\GoRMG\Subcontract\Actions\DyeingProcessActions;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubTextileOrder;

class SyncBatchCreationAction
{
    public function syncBatchFromBatchDetail(SubDyeingBatch $dyeingBatch): void
    {
        $orderIdsJson = $dyeingBatch->batchDetails->map(function ($collection) {
            return [
                'sub_textile_order_id' => $collection->sub_textile_order_id,
                'sub_textile_order_detail_id' => $collection->sub_textile_order_detail_id,
            ];
        });

        $firstBatchDetail = $dyeingBatch->batchDetails->first();

        $orderIdsJson = collect($orderIdsJson)->unique('sub_textile_order_detail_id');
        $orderIds = collect($orderIdsJson)->pluck('sub_textile_order_id');
        $orderNos = SubTextileOrder::query()->whereIn('id', $orderIds)->pluck('order_no');
        $subDyeingUnitId = $firstBatchDetail['sub_dyeing_unit_id'] ?? null;
        $fabricCompositionId = $firstBatchDetail['fabric_composition_id'] ?? null;
        $fabricTypeId = $firstBatchDetail['fabric_type_id'] ?? null;
        $colorId = $firstBatchDetail['color_id'] ?? null;
        $ldNo = $firstBatchDetail['ld_no'] ?? null;
        $colorTypeId = $firstBatchDetail['color_type_id'] ?? null;
        $finisDia = $firstBatchDetail['finish_dia'] ?? null;
        $diaTypeId = $firstBatchDetail['dia_type_id'] ?? null;
        $gsm = $firstBatchDetail['gsm'] ?? null;
        $materialDescription = $firstBatchDetail['material_description'] ?? null;
        $unitOfMeasurementId = $firstBatchDetail['unit_of_measurement_id'] ?? null;
        $totalBatchWeight = $dyeingBatch->batchDetails()->sum('batch_weight');
        $fabricColor = $dyeingBatch->batchDetails()->first()['color_id'] ?? null;

        $dyeingBatch->update([
            'sub_textile_order_ids' => $orderIdsJson,
            'order_nos' => $orderNos,
            'sub_dyeing_unit_id' => $subDyeingUnitId,
            'fabric_composition_id' => $fabricCompositionId,
            'fabric_type_id' => $fabricTypeId,
            'color_id' => $colorId,
            'ld_no' => $ldNo,
            'color_type_id' => $colorTypeId,
            'finish_dia' => $finisDia,
            'dia_type_id' => $diaTypeId,
            'gsm' => $gsm,
            'material_description' => $materialDescription,
            'unit_of_measurement_id' => $unitOfMeasurementId,
            'total_batch_weight' => $totalBatchWeight,
//            'fabric_color' => $fabricColor,
        ]);
    }

    public function syncBatchFromMachineAllocation(SubDyeingBatch $dyeingBatch): void
    {
        $totalMachineCapacity = 0;
        $dyeingBatch->machineAllocations->each(function ($collection) use (&$totalMachineCapacity) {
            $totalMachineCapacity += $collection->machine->capacity;
        });
        $dyeingBatch->update(['total_machine_capacity' => $totalMachineCapacity]);
    }
}
