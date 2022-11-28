<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Services;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\FinishingTarget;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\HourWiseFinishingProduction;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingFloor;

class HourlyFinishingProductionReportService
{
    protected $date, $floor_id, $total = ["IRON" => ["total" => 0], "PACKING" => ["total" => 0], "POLY" => ["total" => 0]];

    public function __construct(Request $request)
    {
        $this->date = $request->get('date') ?? date('Y-m-d');
        $this->floor_id = FinishingFloor::query()->where('name', $request->get('floor_no'))->value('id') ?? null;
    }

    /**
     * @return array
     */
    public function report(): array
    {
        $targets = FinishingTarget::query()
                ->with(['buyer', 'order', 'item', 'floor'])
                ->when($this->floor_id, function ($q) {
                    return $q->where('finishing_floor_id', $this->floor_id);
                })
                ->whereDate('production_date', $this->date)
                ->get() ?? [];

        $productions = HourWiseFinishingProduction::query()
                ->with('color')
                ->when($this->floor_id, function ($q) {
                    return $q->where('finishing_floor_id', $this->floor_id);
                })
                ->whereDate('production_date', $this->date)
                ->whereIn('production_type', [
                    HourWiseFinishingProduction::IRON,
                    HourWiseFinishingProduction::POLY,
                    HourWiseFinishingProduction::PACKING
                ])
                ->get() ?? [];
        if ($targets == []) {
            return [];
        }
        return $this->combineTargetWiseProductions($targets, $productions);
    }


    /**
     * @param $targets
     * @param $productions
     * @return array
     */
    protected function combineTargetWiseProductions($targets, $productions): array
    {
        return collect($targets)->sortBy('floor.sorting')->groupBy('finishing_floor_id')
            ->map(function ($floorWiseTarget) use ($productions) {

                $targetProductions = [
                    'iron' => [],
                    'poly' => [],
                    'packing' => [],
                ];

                collect($floorWiseTarget)
                    ->flatMap(function ($target) use ($productions, &$targetProductions) {

                        $ironPolyPackingProduction = collect($productions)->where('factory_id', $target->factory_id)
                            ->where('buyer_id', $target->buyer_id)
                            ->where('order_id', $target->order_id)
                            ->where('finishing_floor_id', $target->finishing_floor_id)
                            ->where('item_id', $target->item_id)
                            ->values();
                        collect($ironPolyPackingProduction)->groupBy('color_id')->map(function ($collection) use ($target, &$targetProductions) {

                            $targetDetails = [
                                'floor' => $target->floor->name ?? "",
                                'buyer' => $target->buyer->name ?? "",
                                'style' => $target->order->style_name ?? "",
                                'item' => $target->item->name ?? "",
                                'item_group' => $target->item_group ?? "",
                                'target_id' => $target->id,
                                'color' => $collection->first()->color->name ?? "",
                            ];

                            if ($collection->count() > 0) {
                                $ironProduction = collect($collection)
                                    ->where('production_type', HourWiseFinishingProduction::IRON);
                                $targetProductions[HourWiseFinishingProduction::IRON][] = $this->productionFormatter($targetDetails, $target, $ironProduction);

                                $polyProduction = collect($collection)
                                    ->where('production_type', HourWiseFinishingProduction::POLY);
                                $targetProductions[HourWiseFinishingProduction::POLY][] = $this->productionFormatter($targetDetails, $target, $polyProduction);

                                $packingProduction = collect($collection)
                                    ->where('production_type', HourWiseFinishingProduction::PACKING);
                                $targetProductions[HourWiseFinishingProduction::PACKING][] = $this->productionFormatter($targetDetails, $target, $packingProduction);

                            } else {
                                return false;
                            }
                        });

                    });
                return $targetProductions;
            })->where('iron', '!=', [])->toArray();
    }

    /**
     * @param $targetDetails
     * @param $target
     * @param $production
     * @return array
     */
    protected function productionFormatter($targetDetails, $target, $production): array
    {
        $productionType = $production->first()->production_type;
        $productionTypeUpperCase = strtoupper($productionType);

        $formattedProduction = [
            'man_power' => $target[$productionType . '_man_power'] ?? 0,
            'smv' => $target[$productionType . '_smv'] ?? 0,
            'hr_target' => $target[$productionType . '_hour_target'] ?? 0,
            'process' => $productionTypeUpperCase,
            'hour_0' => $production->sum('hour_0') ?? 0,
            'hour_1' => $production->sum('hour_1') ?? 0,
            'hour_2' => $production->sum('hour_2') ?? 0,
            'hour_3' => $production->sum('hour_3') ?? 0,
            'hour_4' => $production->sum('hour_4') ?? 0,
            'hour_5' => $production->sum('hour_5') ?? 0,
            'hour_6' => $production->sum('hour_6') ?? 0,
            'hour_7' => $production->sum('hour_7') ?? 0,
            'hour_8' => $production->sum('hour_8') ?? 0,
            'hour_9' => $production->sum('hour_9') ?? 0,
            'hour_10' => $production->sum('hour_10') ?? 0,
            'hour_11' => $production->sum('hour_11') ?? 0,
            'hour_12' => $production->sum('hour_12') ?? 0,
            'hour_13' => $production->sum('hour_13') ?? 0,
            'hour_14' => $production->sum('hour_14') ?? 0,
            'hour_15' => $production->sum('hour_15') ?? 0,
            'hour_16' => $production->sum('hour_16') ?? 0,
            'hour_17' => $production->sum('hour_17') ?? 0,
            'hour_18' => $production->sum('hour_18') ?? 0,
            'hour_19' => $production->sum('hour_19') ?? 0,
            'hour_20' => $production->sum('hour_20') ?? 0,
            'hour_21' => $production->sum('hour_21') ?? 0,
            'hour_22' => $production->sum('hour_22') ?? 0,
            'hour_23' => $production->sum('hour_23') ?? 0,
        ];

        for ($i = 0; $i < 24; $i++) {
            $this->total[$productionTypeUpperCase]['hour_' . $i] = ($this->total[$productionTypeUpperCase]['hour_' . $i] ?? 0) + $formattedProduction['hour_' . $i];
            $this->total[$productionTypeUpperCase]['total'] += ($formattedProduction['hour_' . $i]);
        }

        return $targetDetails + $formattedProduction;
    }

    public function getTotal(): array
    {
        return $this->total;
    }
}
