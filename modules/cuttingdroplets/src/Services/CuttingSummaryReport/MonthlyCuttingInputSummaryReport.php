<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services\CuttingSummaryReport;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class MonthlyCuttingInputSummaryReport extends CuttingInputSummary
{

    public function init($date)
    {
        $this->date = $date;
        $this->startDate = Carbon::make($this->date)->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::make($this->date)->endOfMonth()->format('Y-m-d');
        $this->dateRange = CarbonPeriod::create($this->startDate, $this->endDate);

        return $this;
    }

    public function format(): array
    {
        $report = [];
        $cuttingFloors = $this->cuttingData->unique('cutting_floor_id')->pluck('cuttingFloor');
        $inputFloors = $this->inputData->unique('floor_id')->pluck('floorWithoutGlobalScopes');
        $printEmbrFloors = $this->printEmbrData->unique('cutting_floor_id')->pluck('cuttingFloorWithoutGlobalScopes');

        foreach ($this->dateRange as $date) {
            $date = $date->format('Y-m-d');

            collect($printEmbrFloors)
                ->map(function ($floor) use ($date, &$report) {

                    $report[$date]['print_sent'][$floor->floor_no] = $this->printEmbrData->where('production_date', $date)
                            ->where('cutting_floor_id', $floor->id)
                            ->sum('print_sent_qty_sum') ?? 0;

                    $report[$date]['print_received'][$floor->floor_no] = $this->printEmbrData->where('production_date', $date)
                            ->where('cutting_floor_id', $floor->id)
                            ->sum('print_received_qty_sum') ?? 0;

                    $report[$date]['embr_sent'][$floor->floor_no] = $this->printEmbrData->where('production_date', $date)
                            ->where('cutting_floor_id', $floor->id)
                            ->sum('embroidery_sent_qty_sum') ?? 0;

                    $report[$date]['embr_received'][$floor->floor_no] = $this->printEmbrData->where('production_date', $date)
                            ->where('cutting_floor_id', $floor->id)
                            ->sum('embroidery_received_qty_sum') ?? 0;

                });

            collect($cuttingFloors)
                ->map(function ($floor) use ($date, &$report) {

                    $report[$date][$floor->floor_no] = $this->cuttingData->where('production_date', $date)
                            ->where('cutting_floor_id', $floor->id)
                            ->sum('cutting_qty_sum') ?? 0;

                });

            collect($inputFloors)
                ->map(function ($floor) use ($date, &$report) {

                    $report[$date][$floor->floor_no] = $this->inputData->where('production_date', $date)
                            ->where('floor_id', $floor->id)
                            ->sum('sewing_input_sum') ?? 0;

                });

            $report[$date]['total_print_sent'] = $this->printEmbrData
                    ->where('production_date', $date)
                    ->sum('print_sent_qty_sum') ?? 0;

            $report[$date]['total_print_rec'] = $this->printEmbrData
                    ->where('production_date', $date)
                    ->sum('print_received_qty_sum') ?? 0;

            $report[$date]['total_embr_sent'] = $this->printEmbrData
                    ->where('production_date', $date)
                    ->sum('embroidery_sent_qty_sum') ?? 0;

            $report[$date]['total_embr_rec'] = $this->printEmbrData
                    ->where('production_date', $date)
                    ->sum('embroidery_received_qty_sum') ?? 0;

            $report[$date]['total_cutting'] = $this->cuttingData
                    ->where('production_date', $date)
                    ->sum('cutting_qty_sum') ?? 0;

            $report[$date]['total_input'] = $this->inputData
                    ->where('production_date', $date)
                    ->sum('sewing_input_sum') ?? 0;
        }

        return [
            'report' => $report,
            'cuttingFloors' => $cuttingFloors,
            'printEmbrFloors' => $printEmbrFloors,
            'inputFloors' => $inputFloors
        ];
    }
}
