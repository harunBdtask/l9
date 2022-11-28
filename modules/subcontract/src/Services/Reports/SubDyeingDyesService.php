<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\Reports;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingProduction\SubDyeingProductionDetail;

class SubDyeingDyesService
{
    private $fromDate;
    private $toDate;
    private $dyeingProductionDetail;

    private function __construct($fromDate, $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public static function setDateRange($fromDate, $toDate): self
    {
        return new static($fromDate, $toDate);
    }

    /**
     * @return mixed
     */
    private function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @return mixed
     */
    private function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @return Builder[]|Collection
     */
    private function fetchDyeingProductionDetails()
    {
        $this->dyeingProductionDetail = $this->dyeingProductionDetail ?? SubDyeingProductionDetail::query()
                ->with([
                    'color',
                    'fabricType',
                    'subDyeingProduction.supplier',
                    'subDyeingBatch.machineAllocations.machine',
                    'subDyeingBatch.subDyeingRecipe.recipeDetails',
                    'subDyeingBatch.subDyeingRecipeDetails.dsItem.latestDyesTransaction',
                ])
                ->with(['subDyeingBatch' => function ($query) {
                    return $query->withSum('subDryerDetail', 'total_cost')
                        ->withSum('subSlittingDetail', 'total_cost')
                        ->withSum('subDyeingStenteringDetail', 'total_cost')
                        ->withSum('subDyeingTumbleDetails', 'total_cost')
                        ->withSum('subDyeingPeachDetail', 'total_cost')
                        ->withSum('subCompactorDetail', 'total_cost')
                        ->withSum('subDyeingFinishProductionDetail', 'total_cost')
                        ->withSum('subDyeingTubeCompactingDetail', 'total_cost')
                        ->withSum('subDyeingSqueezerDetail', 'total_cost')
                        ->withSum('subDyeingHtSetDetail', 'total_cost');
                }])
                ->whereHas('subDyeingBatch', function ($query) {
                    $query->whereBetween('batch_date', [$this->getFromDate(), $this->getToDate()]);
                })->get();

        return $this->dyeingProductionDetail;
    }

    /**
     * @param $batch
     * @return mixed
     */
    private function computeOverHeadCost($batch)
    {
        return $batch->sub_dryer_detail_sum_total_cost
            + $batch->sub_slitting_detail_sum_total_cost
            + $batch->sub_dyeing_stentering_detail_sum_total_cost
            + $batch->sub_dyeing_tumble_details_sum_total_cost
            + $batch->sub_dyeing_peach_detail_sum_total_cost
            + $batch->sub_compactor_detail_sum_total_cost
            + $batch->sub_dyeing_finish_production_detail_sum_total_cost
            + $batch->sub_dyeing_tube_compacting_detail_sum_total_cost
            + $batch->sub_dyeing_squeezer_detail_sum_total_cost
            + $batch->sub_dyeing_ht_set_detail_sum_total_cost;
    }

    /**
     * @param $batch
     * @return mixed
     */
    private function calculateDyesChemicalCost($batch)
    {
        return $batch->subDyeingRecipeDetails->sum('dsItem.latestDyesTransaction.rate');
    }

    /**
     * @param $batch
     * @return float|int
     */
    private function calculateTotalCost($batch)
    {
        return $this->calculateDyesChemicalCost($batch) + $this->computeOverHeadCost($batch);
    }

    /**
     * @param $batch
     * @return float|int
     */
    private function calculateTotalValue($batch)
    {
        return $batch->buyer_rate * $this->calculateTotalCost($batch);
    }

    /**
     * @param $batch
     * @return float|int
     */
    private function computePerKgCost($batch)
    {
        $totalDyeingProductionQty = $this->fetchDyeingProductionDetails()
                ->where('batch_id', $batch->id)
                ->sum('dyeing_production_qty') ?? 0;

        return $totalDyeingProductionQty > 0
            ? $this->calculateTotalCost($batch) / $totalDyeingProductionQty
            : 0;
    }

    /**
     * @param $dyeingProduction
     * @return Carbon
     */
    private function getLoadingDate($dyeingProduction): ?Carbon
    {
        return $dyeingProduction->loading_date ?
            Carbon::parse($dyeingProduction->loading_date) : null;
    }

    /**
     * @param $dyeingProduction
     * @return Carbon
     */
    private function getUnloadingDate($dyeingProduction): ?Carbon
    {
        return $dyeingProduction->unloading_date ?
            Carbon::parse($dyeingProduction->unloading_date) : null;
    }

    /**
     * @param $dyeingProduction
     * @return string
     */
    private function getDuration($dyeingProduction): string
    {
        $duration = $this->getLoadingDate($dyeingProduction) && $this->getUnloadingDate($dyeingProduction) ?
            $this->getLoadingDate($dyeingProduction)->diff($this->getUnloadingDate($dyeingProduction)) : '';

        $totalDiffDays = $duration ? $duration->d . 'd ' : '';
        $totalDiffHours = $duration ? $duration->h . 'h' : '';

        return $totalDiffDays . ' ' . $totalDiffHours;
    }

    private function formatDyeingProductionDetails()
    {
        return $this->fetchDyeingProductionDetails()->map(function ($collection) {
            $batch = $collection->subDyeingBatch;
            $dyeingProduction = $collection->subDyeingProduction;
            $batchBuyerRate = $collection->subDyeingBatch->batchBuyerRate;

            $buyerRate = collect($batchBuyerRate)
                ->where('dia_type_id', $collection->dia_type_value['id'])
                ->first();

            $machineNo = $batch->machineAllocations
                ->pluck('machine.name')
                ->unique()
                ->implode(', ');

            $date = $batch->batch_date ?
                Carbon::parse($batch->batch_date) : null;

            return [
                'date' => optional($date)->format('d M Y'),
                'mc_no' => $machineNo ?? null,
                'buyer' => $dyeingProduction->supplier->name,
                'order' => $collection->order_no,
                'fabric_type' => $collection->fabricType->construction_name,
                'dia_type' => $collection->dia_type_value['name'],
                'batch_no' => $collection->batch_no,
                'color' => $collection->color->name,
                'production_qty' => $collection->dyeing_production_qty,
                'tube' => $dyeingProduction->tube,
                'loading_time' => optional($this->getLoadingDate($dyeingProduction))->format('d M Y h:ia'),
                'unloading_time' => optional($this->getUnloadingDate($dyeingProduction))->format('d M Y h:ia'),
                'duration' => $this->getDuration($dyeingProduction),
                'remarks' => $dyeingProduction->remarks,
                'dyes_chemical_cost' => $this->calculateDyesChemicalCost($batch) ?? 0,
                'overhead_cost' => $this->computeOverHeadCost($batch),
                'total_cost' => $this->calculateTotalCost($batch),
                'per_keg_cost' => $this->computePerKgCost($batch),
                'rate' => $buyerRate->rate ?? 0,
                'total_value' => $this->calculateTotalValue($batch),
            ];
        });
    }

    /**
     * @return Builder[]|Collection|\Illuminate\Support\Collection
     */
    public function generateReport()
    {
        return $this->formatDyeingProductionDetails()->groupBy('batch_no');
    }
}
