<?php

namespace SkylarkSoft\GoRMG\Subcontract\DTO;

use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingHtSetDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingSqueezerDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\DyeingProcess\SubDyeingTubeCompactingDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubCompactorDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDryerDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingFinishingProduction\SubDyeingFinishingProductionDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingPeach\SubDyeingPeachDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingStenteringDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubDyeingTumble\SubDyeingTumbleDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubSlittingDetail;

class SubDyeingFinishingProductionDailyDTO
{
    private $fromDate;
    private $toDate;
    private $sliting;
    private $stenterings;
    private $subCompactors;
    private $subDyeingTubeCompactings;
    private $subDryers;
    private $subDyeingSqueezers;
    private $subDyeingPeachs;
    private $brushes;
    private $subDyeingTumbles;
    private $subDyeingHtSets;

    public function setDateRange($fromDate, $toDate): self
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;

        return $this;
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

    public function generateDateRange(): CarbonPeriod
    {
        return CarbonPeriod::create($this->getFromDate(), "1 day", $this->getToDate());
    }

    public function getSliting()
    {
        $this->sliting = $this->sliting ?? SubSlittingDetail::query()
                ->with('subSlitting.machine:id,name')
                ->selectRaw('Sum(finish_qty) AS total_finish_qty,production_date,sub_slitting_id')
                ->whereBetween('production_date', [$this->getFromDate(), $this->getToDate()])
                ->groupBy('production_date', 'sub_slitting_id')
                ->get();

        return $this->sliting;
    }

    public function getStenterings()
    {
        $this->stenterings = $this->stenterings ?? SubDyeingStenteringDetail::query()
                ->with('subDyeingStentering.machine:id,name')
                ->selectRaw('Sum(finish_qty) AS total_finish_qty,production_date,sub_stentering_id')
                ->whereBetween('production_date', [$this->getFromDate(), $this->getToDate()])
                ->groupBy('production_date', 'sub_stentering_id')
                ->get();

        return $this->stenterings;
    }

    public function getCompactor()
    {
        $this->subCompactors = $this->subCompactors ?? SubCompactorDetail::query()
                ->with('subCompactor.machine:id,name')
                ->selectRaw('Sum(finish_qty) AS total_finish_qty,production_date,sub_compactor_id')
                ->whereBetween('production_date', [$this->getFromDate(), $this->getToDate()])
                ->groupBy('production_date', 'sub_compactor_id')
                ->get();

        return $this->subCompactors;
    }

    public function getTubeCompactings()
    {
        $this->subDyeingTubeCompactings = $this->subDyeingTubeCompactings ?? SubDyeingTubeCompactingDetail::query()
                ->with('subDyeingTubeCompacting.machine:id,name')
                ->selectRaw('Sum(finish_qty) AS total_finish_qty,production_date,sub_dyeing_tube_compacting_id')
                ->whereBetween('production_date', [$this->getFromDate(), $this->getToDate()])
                ->groupBy('production_date', 'sub_dyeing_tube_compacting_id')
                ->get();

        return $this->subDyeingTubeCompactings;
    }

    public function getDryers()
    {
        $this->subDryers = $this->subDryers ?? SubDryerDetail::query()
                ->with('subDryer.machine:id,name')
                ->selectRaw('Sum(finish_qty) AS total_finish_qty,production_date,sub_dryer_id')
                ->whereBetween('production_date', [$this->getFromDate(), $this->getToDate()])
                ->groupBy('production_date', 'sub_dryer_id')
                ->get();

        return $this->subDryers;
    }

    public function getSqueezers()
    {
        $this->subDyeingSqueezers = $this->subDyeingSqueezers ?? SubDyeingSqueezerDetail::query()
                ->with('subDyeingSqueezer.machine:id,name')
                ->selectRaw('Sum(finish_qty) AS total_finish_qty,production_date,sub_dyeing_squeezer_id')
                ->whereBetween('production_date', [$this->getFromDate(), $this->getToDate()])
                ->groupBy('production_date', 'sub_dyeing_squeezer_id')
                ->get();

        return $this->subDyeingSqueezers;
    }

    public function getPeachs()
    {
        $this->subDyeingPeachs = $this->subDyeingPeachs ?? SubDyeingPeachDetail::query()
                ->with('peach.machine:id,name')
                ->selectRaw('Sum(finish_qty) AS total_finish_qty,production_date,sub_dyeing_peach_id')
                ->whereBetween('production_date', [$this->getFromDate(), $this->getToDate()])
                ->groupBy('production_date', 'sub_dyeing_peach_id')
                ->get();

        return $this->subDyeingPeachs;
    }

    public function getBrushes()
    {
        $this->brushes = $this->brushes ?? SubDyeingFinishingProductionDetail::query()
                ->with('finishingProduction.machine:id,name')
                ->selectRaw('Sum(finish_qty) AS total_finish_qty,production_date,sub_dyeing_finishing_production_id')
                ->whereBetween('production_date', [$this->getFromDate(), $this->getToDate()])
                ->groupBy('production_date', 'sub_dyeing_finishing_production_id')
                ->get();

        return $this->brushes;
    }

    public function getDyeingTumbles()
    {
        $this->subDyeingTumbles = $this->subDyeingTumbles ?? SubDyeingTumbleDetail::query()
                ->selectRaw('Sum(finish_qty) AS total_finish_qty,production_date,sub_dyeing_tumble_id')
                ->whereBetween('production_date', [$this->getFromDate(), $this->getToDate()])
                ->groupBy('production_date')
                ->get();

        return $this->subDyeingTumbles;
    }

    public function getHtSet()
    {
        $this->subDyeingHtSets = $this->subDyeingHtSets ?? SubDyeingHtSetDetail::query()
                ->with('subDyeingHtSet.machine:id,name')
                ->selectRaw('Sum(finish_qty) AS total_finish_qty,production_date,sub_dyeing_ht_set_id')
                ->whereBetween('production_date', [$this->getFromDate(), $this->getToDate()])
                ->groupBy('production_date', 'sub_dyeing_ht_set_id')
                ->get();

        return $this->subDyeingHtSets;
    }

    public function pluckSlittingsMachine(): Collection
    {
        return $this->getSliting()->pluck('subSlitting.machine', 'subSlitting.machine_id');
    }

    public function pluckStenteringsMachine(): Collection
    {
        return $this->getStenterings()->pluck('subDyeingStentering.machine', 'subDyeingStentering.machine_id');
    }

    public function pluckSubCompactorsMachine(): Collection
    {
        return $this->getCompactor()->pluck('subCompactor.machine', 'subCompactor.machine_id');
    }

    public function pluckSubTubeCompactingsMachine(): Collection
    {
        return $this->getTubeCompactings()->pluck('subDyeingTubeCompacting.machine', 'subDyeingTubeCompacting.machine_id');
    }

    public function pluckSubDryersMachine(): Collection
    {
        return $this->getDryers()->pluck('subDryer.machine', 'subDryer.machine_id');
    }

    public function pluckSubSqueezersMachine(): Collection
    {
        return $this->getSqueezers()->pluck('subDyeingSqueezer.machine', 'subDyeingSqueezer.machine_id');
    }

    public function pluckSubPeachsMachine(): Collection
    {
        return $this->getPeachs()->pluck('peach.machine', 'peach.machine_id');
    }

    public function pluckSubBrushesMachine(): Collection
    {
        return $this->getBrushes()->pluck('finishingProduction.machine', 'finishingProduction.machine_id');
    }

    public function pluckSubDyeingHtSetMachine(): Collection
    {
        return $this->getHtSet()->pluck('subDyeingHtSet.machine', 'subDyeingHtSet.machine_id');
    }
}
