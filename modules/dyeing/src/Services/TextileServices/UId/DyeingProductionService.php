<?php

namespace SkylarkSoft\GoRMG\Dyeing\Services\TextileServices\UId;

use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingBatch\DyeingBatch;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingProduction\DyeingProduction;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\DyeingProduction\DyeingProductionDetail;

class DyeingProductionService
{

    public static function generateUniqueId(): string
    {
        $prefix = DyeingProduction::query()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'DP-' . date('y') . '-' . $generate;
    }

    /**
     * @param DyeingBatch $dyeingBatch
     * @return array
     */
    public function fetchBatchDetailsData(DyeingBatch $dyeingBatch): array
    {
        $batchData = [
            'dyeing_unit_id' => $dyeingBatch->sub_dyeing_unit_id,
            'dyeing_unit' => $dyeingBatch->subDyeingUnit->name ?? null,
            'machine_name' => collect($dyeingBatch->machineAllocations)
                    ->pluck('machine.name')->implode(', ') ?? null,
            'shift_id' => $dyeingBatch->shift_id,
            'production_date' => date('Y-m-d'),
            'loading_date' => null,
            'unloading_date' => null,
            'remarks' => null,
            'dyeing_production_details' => []
        ];

        foreach ($dyeingBatch->dyeingBatchDetails as $detail) {
            $prevQty = $this->previousQty($detail['id']);
            $batchData['dyeing_production_details'][] = $this->formatProductionDetails($detail, $prevQty);
        }

        return $batchData;
    }

    public function previousQty($batchDetailsId, $ownId = null)
    {
        return DyeingProductionDetail::query()
            ->selectRaw('SUM(no_of_roll) AS prev_roll,SUM(dyeing_production_qty) AS prev_production_qty')
            ->where('dyeing_batch_detail_id', $batchDetailsId)
            ->when($ownId, function ($q, $ownId) {
                $q->where('id', '!=', $ownId);
            })
            ->groupBy('dyeing_batch_detail_id')
            ->first();
    }

    public function formatProductionDetails($detail, $prevQty): array
    {
        return [
            'id' => $detail->id,
            'dyeing_order_no' => $detail->textileOrder->unique_id,
            'dyeing_order_id' => $detail->textile_order_id,
            'dyeing_batch_id' => $detail->dyeing_batch_id,
            'dyeing_batch_no' => $detail->dyeingBatch->batch_no,
            'dyeing_batch_details_id' => $detail->id,
            'fabric_description' => $detail->fabric_description,
            'dia_type' => $detail->dia_type_value,
            'gsm' => $detail->gsm,
            'fabric_color' => $detail->color->name ?? null,
            'fabric_composition_id' => $detail->fabric_composition_id,
            'fabric_type_id' => $detail->fabric_type_id,
            'color_id' => $detail->color_id,
            'ld_no' => $detail->ld_no,
            'color_type_id' => $detail->color_type_id,
            'finish_dia' => $detail->finish_dia,
            'dia_type_id' => $detail->dia_type_id,
            'batch_qty' => $detail->order_qty,
            'production_date' => date('Y-m-d'),
            'no_of_roll' => null,
            'prev_no_of_roll' => $prevQty->prev_roll ?? 0,
            'dyeing_production_qty' => null,
            'prev_dyeing_production_qty' => $prevQty->prev_production_qty ?? 0,
            'reject_roll_qty' => null,
            'reject_qty' => null,
            'unit_cost' => null,
            'total_cost' => null,
        ];
    }

}
