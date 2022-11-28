<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services\CuttingSummaryReport;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateFloorWisePrintEmbrReport;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintEmbrProductionReport;

abstract class CuttingInputSummary
{
    protected $date;
    protected $startDate, $endDate;
    protected $dateRange;
    protected $cuttingData;
    protected $printEmbrData;
    protected $inputData;

    public function generate()
    {
        $this->cuttingData = $this->cuttingDataGenerator();
        $this->printEmbrData = $this->printEmbrDataGenerator();
        $this->inputData = $this->inputDataGenerator();
        $this->format();
        return $this->format();
    }

    public function cuttingDataGenerator()
    {
        return DateTableWiseCutProductionReport::query()
            ->with('cuttingFloor')
            ->selectRaw(DB::raw('*,SUM(cutting_qty) AS cutting_qty_sum'))
            ->groupBy('production_date', 'cutting_floor_id')
            ->whereBetween('production_date', [$this->startDate, $this->endDate])
            ->get();
    }

    public function printEmbrDataGenerator()
    {
        return DateFloorWisePrintEmbrReport::query()
            ->selectRaw(DB::raw('*,SUM(print_sent_qty) AS print_sent_qty_sum,SUM(print_received_qty) AS print_received_qty_sum,SUM(embroidery_sent_qty) AS embroidery_sent_qty_sum,SUM(embroidery_received_qty) AS embroidery_received_qty_sum'))
            ->groupBy('production_date', 'cutting_floor_id')
            ->whereBetween('production_date', [$this->startDate, $this->endDate])
            ->get();
    }

    public function inputDataGenerator()
    {
        return FinishingProductionReport::query()
            ->with('floorWithoutGlobalScopes')
            ->selectRaw(DB::raw('*,SUM(sewing_input) AS sewing_input_sum'))
            ->groupBy('production_date', 'floor_id')
            ->whereBetween('production_date', [$this->startDate, $this->endDate])
            ->get();
    }

    public abstract function init($date);

    public abstract function format();
}
