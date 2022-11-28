<?php

namespace SkylarkSoft\GoRMG\Subcontract\DTO;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalTransaction;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\DyeingRecipe\SubDyeingRecipeDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingBatch;

class SubDyeingBatchCostingDTO
{
    private $fromDate;
    private $toDate;
    private $batch;

    /**
     * @return mixed
     */
    private function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @param mixed $fromDate
     */
    public function setFromDate($fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * @return mixed
     */
    private function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @param mixed $toDate
     */
    public function setToDate($toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * @return mixed
     */
    private function getBatch()
    {
        return $this->batch;
    }

    /**
     * @param mixed $batch
     */
    public function setBatch($batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    /**
     * @return Builder[]|Collection
     */
    private function getBatchRecipeDetails()
    {
        $recipeDetails = $recipeDetails ?? SubDyeingRecipeDetail::query()
                ->selectRaw("*,SUM(total_qty) as totalQty")
                ->with(['recipe.subDyeingBatch', 'dsItem', 'unitOfMeasurement'])
                ->when($this->getFromDate(), function ($query) {
                    $query->whereHas('recipe.subDyeingBatch', function ($q) {
                        $q->whereBetween('batch_date', [$this->getFromDate(), $this->getToDate()]);
                    });
                })
                ->when($this->getBatch(), function ($query) {
                    $query->whereHas('recipe', function ($q) {
                        $q->where('batch_id', $this->getBatch());
                    });
                })
                ->groupBy('item_id')->get();

        return $recipeDetails;
    }

    private function getBatchDetails()
    {
        $batchDetails = $batchDetails ?? SubDyeingBatch::query()
                ->with([
                    'machineAllocations.machine',
                    'supplier',
                    'fabricColor',
                    'fabricType',
                    'subDyeingRecipe.recipeRequisitions',
                    'subDyeingProduction.shift',
                ])
                ->findOrFail($this->getBatch());

        return $batchDetails;
    }

    public function formatBatchDetails(): array
    {
        $batchDetails = $this->getBatchDetails() ?? [];

        $requisitionNo = $batchDetails->subDyeingRecipe
            ->pluck('recipeRequisitions')->flatten()
            ->pluck('requisition_uid')->unique()
            ->implode(', ');

        $recipeNo = $batchDetails->subDyeingRecipe
            ->pluck('recipe_uid')->unique()
            ->implode(', ');

        return [
            'batch_no' => $batchDetails->batch_no,
            'batch_date' => $batchDetails->batch_date,
            'dyeing_mc' => $batchDetails->machineAllocations->pluck('machine.name')->unique()->implode(', '),
            'machine_capacity' => $batchDetails->total_machine_capacity,
            'grey_weight' => $batchDetails->total_batch_weight,
            'order_no' => $batchDetails->order_nos ? implode($batchDetails->order_nos, ', ') : '',
            'party' => $batchDetails->supplier->name,
            'color' => $batchDetails->fabricColor->name,
            'gsm' => $batchDetails->gsm,
            'fabric_type' => $batchDetails->fabricType->construction_name,
            'recipe_no' => $recipeNo,
            'requisition_no' => $requisitionNo,
            'yarn_lot' => $batchDetails->subDyeingRecipe->pluck('yarn_lot')->unique()->implode(', '),
            'shift' => $batchDetails->subDyeingProduction->pluck('shift.shift_name')->unique()->implode(', '),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws Exception
     */
    public function formatReport(): \Illuminate\Support\Collection
    {
        $report = [];
        $items = $this->getBatchRecipeDetails()->pluck('item_id');
        $itemRates = DyesChemicalTransaction::query()
            ->whereIn('item_id', $items)
            ->latest()
            ->get();
        foreach ($this->getBatchRecipeDetails() as $recipeDetail) {
            $rate = $itemRates->where('item_id', $recipeDetail->item_id)->first()->rate ?? 0;

            $recipeBatch = $recipeDetail->recipe->subDyeingBatch ?? [];
            $report[] = [
                'date' => $recipeBatch ? $recipeBatch->batch_date : '',
                'batch_no' => $recipeBatch ? $recipeBatch->batch_no : '',
                'batch_qty' => $recipeBatch ? $recipeBatch->total_batch_weight : '',
                'item' => $recipeDetail->dsItem->name,
                'unit' => $recipeDetail->unitOfMeasurement->name ?? 'KG',
                'qty' => $recipeDetail->total_qty,
                'rate' => $rate,
                'value' => $recipeDetail->total_qty * $rate,
            ];
        }

        return collect($report)->sortBy('date')->groupBy('batch_no');
    }
}
