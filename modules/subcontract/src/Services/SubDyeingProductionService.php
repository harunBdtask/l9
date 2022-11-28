<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services;

use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProduction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProductionDetail;

class SubDyeingProductionService
{
    public static function generateUniqueId(): string
    {
        $prefix = SubDyeingProduction::withTrashed()->max('id') + 1;
        $generate = str_pad($prefix, 5, "0", STR_PAD_LEFT);

        return getPrefix() . 'SDP-' . date('y') . '-' . $generate;
    }

    /**
     * @param SubDyeingBatch $subDyeingBatch
     * @return array
     */
    public function fetchBatchDetailsData(SubDyeingBatch $subDyeingBatch): array
    {
        $batchData = [
            'dyeing_unit_id' => $subDyeingBatch->sub_dyeing_unit_id,
            'dyeing_unit' => $subDyeingBatch->subDyeingUnit->name ?? null,
            'machine_name' => collect($subDyeingBatch->machineAllocations)
                    ->pluck('machine.name')->implode(', ') ?? null,
            'shift_id' => null,
            'production_date' => date('Y-m-d'),
            'loading_date' => null,
            'unloading_date' => null,
            'remarks' => null,
            'details' => [],
        ];

        foreach ($subDyeingBatch->batchDetails as $detail) {
            $prevQty = $this->previousQty($detail['id']);
            $batchData['details'][] = $this->formatProductionDetails($detail, $prevQty);
        }

        return $batchData;
    }

    public function formatProductionDetails($detail, $prevQty): array
    {
        return [
            'id' => $detail->id,
            'order_no' => $detail->subTextileOrder->order_no,
            'order_id' => $detail->sub_textile_order_id,
            'batch_id' => $detail->sub_dyeing_batch_id,
            'batch_no' => $detail->subDyeingBatch->batch_no,
            'batch_details_id' => $detail->id,
            'fabric_description' => $detail->fabric_composition_value,
            'dia_type' => $detail->dia_type_value['name'],
            'gsm' => $detail->gsm,
            'fabric_color' => $detail->subDyeingBatch->fabricColor->name ?? null,
            'fabric_composition_id' => $detail->fabric_composition_id,
            'fabric_type_id' => $detail->fabric_type_id,
            'color_id' => $detail->subDyeingBatch->fabric_color,
            'ld_no' => $detail->ld_no,
            'color_type_id' => $detail->color_type_id,
            'finish_dia' => $detail->finish_dia,
            'dia_type_id' => $detail->dia_type_id,
            'batch_qty' => $detail->batch_weight,
            'production_date' => date('Y-m-d'),
            'no_of_roll' => 0,
            'prev_no_of_roll' => $prevQty->prev_roll ?? 0,
            'dyeing_production_qty' => null,
            'prev_dyeing_production_qty' => $prevQty->prev_production_qty ?? 0,
            'reject_roll_qty' => null,
            'reject_qty' => null,
            'unit_cost' => 0,
            'total_cost' => null,
        ];
    }

    public function previousQty($batchDetailsId, $ownId = null)
    {
        return SubDyeingProductionDetail::query()
            ->selectRaw('SUM(no_of_roll) AS prev_roll,SUM(dyeing_production_qty) AS prev_production_qty')
            ->where('batch_details_id', $batchDetailsId)
            ->when($ownId, function ($q, $ownId) {
                $q->where('id', '!=', $ownId);
            })
            ->groupBy('batch_details_id')
            ->first();
    }

    /**
     * @param SubDyeingProduction $subDyeingProduction
     * @return array
     */
    public function formatProduction(SubDyeingProduction $subDyeingProduction): array
    {
        $formattedProduction = [
            'id' => $subDyeingProduction->id,
            'factory' => $subDyeingProduction->factory->factory_name ?? null,
            'factory_id' => $subDyeingProduction->factory_id,
            'supplier' => $subDyeingProduction->supplier->name ?? null,
            'supplier_id' => $subDyeingProduction->supplier_id,
            'order_id' => $subDyeingProduction->order_id,
            'order_no' => $subDyeingProduction->order_no,
            'batch_id' => $subDyeingProduction->batch_id,
            'batch_no' => $subDyeingProduction->batch_no,
            'dyeing_unit_id' => $subDyeingProduction->subDyeingBatch->sub_dyeing_unit_id ?? null,
            'dyeing_unit' => $subDyeingProduction->subDyeingBatch->subDyeingUnit->name ?? null,
            'machine_name' => collect($subDyeingProduction->subDyeingBatch->machineAllocations)
                    ->pluck('machine.name')->implode(', ') ?? null,
            'shift_id' => $subDyeingProduction->shift_id,
            'tube' => $subDyeingProduction->tube,
            'production_date' => $subDyeingProduction->production_date,
            'loading_date' => $subDyeingProduction->loading_date,
            'unloading_date' => $subDyeingProduction->unloading_date,
            'remarks' => $subDyeingProduction->remarks,
        ];

        $formattedProduction['details'] = $subDyeingProduction->subDyeingProductionDetails->map(function ($detail) {
            $prevQty = $this->previousQty($detail->batch_details_id, $detail->id);

            // $detail['fabric_description'] = $detail->fabric_composition_value;
            $detail['fabric_description'] = $detail->batchDetail->material_description;
            $detail['prev_no_of_roll'] = $prevQty->prev_roll ?? 0;
            $detail['prev_dyeing_production_qty'] = $prevQty->prev_production_qty ?? 0;
            $detail['dia_type'] = $detail->dia_type_value['name'] ?? null;
            $detail['fabric_color'] = $detail->color->name ?? null;

            return $detail;
        });

        return $formattedProduction;
    }
}
