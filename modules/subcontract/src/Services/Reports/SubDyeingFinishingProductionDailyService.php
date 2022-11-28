<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\Reports;

class SubDyeingFinishingProductionDailyService
{
    private $subDyeingFinishingProductionDailyDTO;

    private function __construct($subDyeingFinishingProductionDailyDTO)
    {
        $this->subDyeingFinishingProductionDailyDTO = $subDyeingFinishingProductionDailyDTO;
    }

    public static function init($subDyeingFinishingProductionDailyDTO): self
    {
        return new static($subDyeingFinishingProductionDailyDTO);
    }

    private function getDateWiseTotal($dyeingProcessAllData, $date)
    {
        return collect($dyeingProcessAllData)
            ->where('production_date', $date)
            ->sum('total_finish_qty');
    }

    private function generateDateWiseSlittingMachine($currentDate): array
    {
        $slitting = [];
        foreach ($this->subDyeingFinishingProductionDailyDTO->pluckSlittingsMachine() as $key => $machine) {
            $slitting[$machine->name ?? $key] = $this->subDyeingFinishingProductionDailyDTO->getSliting()
                ->where('production_date', $currentDate)
                ->where('subSlitting.machine_id', $machine->id)
                ->sum('total_finish_qty');
        }

        return $slitting;
    }

    private function generateDateWiseStenteringsMachine($currentDate): array
    {
        $stenterings = [];
        foreach ($this->subDyeingFinishingProductionDailyDTO->pluckStenteringsMachine() as $machine) {
            $stenterings[$machine->name] = $this->subDyeingFinishingProductionDailyDTO->getStenterings()
                ->where('production_date', $currentDate)
                ->where('subDyeingStentering.machine_id', $machine->id)
                ->sum('total_finish_qty');
        }

        return $stenterings;
    }

    private function generateDateWiseSubCompactorsMachine($currentDate): array
    {
        $compactors = [];
        foreach ($this->subDyeingFinishingProductionDailyDTO->pluckSubCompactorsMachine() as $machine) {
            $compactors[$machine->name] = $this->subDyeingFinishingProductionDailyDTO->getCompactor()
                ->where('production_date', $currentDate)
                ->where('subCompactor.machine_id', $machine->id)
                ->sum('total_finish_qty');
        }

        return $compactors;
    }

    private function generateDateWiseDryersMachine($currentDate): array
    {
        $dryers = [];
        foreach ($this->subDyeingFinishingProductionDailyDTO->pluckSubDryersMachine() as $machine) {
            $dryers[$machine->name] = $this->subDyeingFinishingProductionDailyDTO->getDryers()
                ->where('production_date', $currentDate)
                ->where('subDryer.machine_id', $machine->id)
                ->sum('total_finish_qty');
        }

        return $dryers;
    }

    private function generateDateWiseTubeCompactingsMachine($currentDate): array
    {
        $tubeCompactings = [];
        foreach ($this->subDyeingFinishingProductionDailyDTO->pluckSubTubeCompactingsMachine() as $machine) {
            $tubeCompactings[$machine->name] = $this->subDyeingFinishingProductionDailyDTO->getTubeCompactings()
                ->where('production_date', $currentDate)
                ->where('subDyeingTubeCompacting.machine_id', $machine->id)
                ->sum('total_finish_qty');
        }

        return $tubeCompactings;
    }

    private function generateDateWiseSqueezersMachine($currentDate): array
    {
        $squeezers = [];
        foreach ($this->subDyeingFinishingProductionDailyDTO->pluckSubSqueezersMachine() as $machine) {
            $squeezers[$machine->name] = $this->subDyeingFinishingProductionDailyDTO->getSqueezers()
                ->where('production_date', $currentDate)
                ->where('subDyeingSqueezer.machine_id', $machine->id)
                ->sum('total_finish_qty');
        }

        return $squeezers;
    }

    private function generateDateWisePeachsMachine($currentDate): array
    {
        $peachs = [];
        foreach ($this->subDyeingFinishingProductionDailyDTO->pluckSubPeachsMachine() as $machine) {
            $peachs[$machine->name] = $this->subDyeingFinishingProductionDailyDTO->getPeachs()
                ->where('production_date', $currentDate)
                ->where('peach.machine_id', $machine->id)
                ->sum('total_finish_qty');
        }

        return $peachs;
    }

    private function generateDateWiseBrushesMachine($currentDate): array
    {
        $brushes = [];
        foreach ($this->subDyeingFinishingProductionDailyDTO->pluckSubBrushesMachine() as $machine) {
            $brushes[$machine->name] = $this->subDyeingFinishingProductionDailyDTO->getBrushes()
                ->where('production_date', $currentDate)
                ->where('finishingProduction.machine_id', $machine->id)
                ->sum('total_finish_qty');
        }

        return $brushes;
    }

    private function generateDateWiseHtSetMachine($currentDate): array
    {
        $htSets = [];
        foreach ($this->subDyeingFinishingProductionDailyDTO->pluckSubDyeingHtSetMachine() as $machine) {
            $htSets[$machine->name] = $this->subDyeingFinishingProductionDailyDTO->getHtSet()
                ->where('production_date', $currentDate)
                ->where('subDyeingHtSet.machine_id', $machine->id)
                ->sum('total_finish_qty');
        }

        return $htSets;
    }

    public function getReportData(): array
    {
        $reportData = [];
        foreach ($this->subDyeingFinishingProductionDailyDTO->generateDateRange() as $date) {
            $currentDate = $date->format('Y-m-d');

            $reportData[$currentDate] = [
                'date' => $date->format('d-M-Y'),
                'total_slitter' => $this->getDateWiseTotal($this->subDyeingFinishingProductionDailyDTO->getSliting(), $currentDate),
                'total_tube_compactings' => $this->getDateWiseTotal($this->subDyeingFinishingProductionDailyDTO->getTubeCompactings(), $currentDate),
                'total_stenterings' => $this->getDateWiseTotal($this->subDyeingFinishingProductionDailyDTO->getStenterings(), $currentDate),
                'total_compactors' => $this->getDateWiseTotal($this->subDyeingFinishingProductionDailyDTO->getCompactor(), $currentDate),
                'total_dryers' => $this->getDateWiseTotal($this->subDyeingFinishingProductionDailyDTO->getDryers(), $currentDate),
                'total_dyeing_squeezers' => $this->getDateWiseTotal($this->subDyeingFinishingProductionDailyDTO->getSqueezers(), $currentDate),
                'total_dyeing_peachs' => $this->getDateWiseTotal($this->subDyeingFinishingProductionDailyDTO->getPeachs(), $currentDate),
                'total_brushes' => $this->getDateWiseTotal($this->subDyeingFinishingProductionDailyDTO->getBrushes(), $currentDate),
                'total_dyeing_tumbles' => $this->getDateWiseTotal($this->subDyeingFinishingProductionDailyDTO->getDyeingTumbles(), $currentDate),
                'total_dyeing_htSets' => $this->getDateWiseTotal($this->subDyeingFinishingProductionDailyDTO->getHtSet(), $currentDate),
            ];

            $reportData[$currentDate]['slittings'] = $this->generateDateWiseSlittingMachine($currentDate);
            $reportData[$currentDate]['stenterings'] = $this->generateDateWiseStenteringsMachine($currentDate);
            $reportData[$currentDate]['compactors'] = $this->generateDateWiseSubCompactorsMachine($currentDate);
            $reportData[$currentDate]['tube_compactings'] = $this->generateDateWiseTubeCompactingsMachine($currentDate);
            $reportData[$currentDate]['dryers'] = $this->generateDateWiseDryersMachine($currentDate);
            $reportData[$currentDate]['squeezers'] = $this->generateDateWiseSqueezersMachine($currentDate);
            $reportData[$currentDate]['peachs'] = $this->generateDateWisePeachsMachine($currentDate);
            $reportData[$currentDate]['brushes'] = $this->generateDateWiseBrushesMachine($currentDate);
            $reportData[$currentDate]['ht_sets'] = $this->generateDateWiseHtSetMachine($currentDate);
        }

        return $reportData;
    }
}
