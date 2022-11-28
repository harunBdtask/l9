<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Services\CuttingSummaryReport;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class YearlyCuttingInputSummaryReport extends CuttingInputSummary
{

    public function init($date)
    {
        $this->date = $date;

        $this->startDate = Carbon::createFromDate($this->date)->startOfYear()->format('Y-m-d');
        $this->endDate = Carbon::createFromDate($this->date)->endOfYear()->format('Y-m-d');
        $this->dateRange = CarbonPeriod::create($this->startDate, '1 Month', $this->endDate);
        return $this;
    }

    public function format(): array
    {
        $report = [];
        $cuttingFloors = $this->cuttingData->unique('cutting_floor_id')->pluck('cuttingFloor');
        $inputFloors = $this->inputData->unique('floor_id')->pluck('floorWithoutGlobalScopes');
        $printEmbrFloors = $this->printEmbrData->unique('cutting_floor_id')->pluck('cuttingFloorWithoutGlobalScopes');

        foreach ($this->dateRange as $date) {

            $startOfMonth = $date->startOfMonth()->format('Y-m-d');
            $endOfMonth = $date->endOfMonth()->format('Y-m-d');
            $monthName = $date->format('F');

            collect($printEmbrFloors)
                ->map(function ($floor) use ($monthName, &$report, $startOfMonth, $endOfMonth) {

                    $report[$monthName]['print_sent'][$floor->floor_no] = $this->printEmbrData
                            ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                            ->where('cutting_floor_id', $floor->id)
                            ->sum('print_sent_qty_sum') ?? 0;

                    $report[$monthName]['print_received'][$floor->floor_no] = $this->printEmbrData
                            ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                            ->where('cutting_floor_id', $floor->id)
                            ->sum('print_received_qty_sum') ?? 0;

                    $report[$monthName]['embr_sent'][$floor->floor_no] = $this->printEmbrData
                            ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                            ->where('cutting_floor_id', $floor->id)
                            ->sum('embroidery_sent_qty_sum') ?? 0;

                    $report[$monthName]['embr_received'][$floor->floor_no] = $this->printEmbrData
                            ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                            ->where('cutting_floor_id', $floor->id)
                            ->sum('embroidery_received_qty_sum') ?? 0;

                });

            collect($cuttingFloors)
                ->map(function ($floor) use ($monthName, &$report, $startOfMonth, $endOfMonth) {

                    $report[$monthName][$floor->floor_no] = $this->cuttingData
                            ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                            ->where('cutting_floor_id', $floor->id)
                            ->sum('cutting_qty_sum') ?? 0;

                });

            collect($inputFloors)
                ->map(function ($floor) use ($monthName, &$report, $startOfMonth, $endOfMonth) {

                    $report[$monthName][$floor->floor_no] = $this->inputData
                            ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                            ->where('floor_id', $floor->id)
                            ->sum('sewing_input_sum') ?? 0;

                });

            $report[$monthName]['total_print_sent'] = $this->printEmbrData
                    ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                    ->sum('print_sent_qty_sum') ?? 0;

            $report[$monthName]['total_print_rec'] = $this->printEmbrData
                    ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                    ->sum('print_received_qty_sum') ?? 0;

            $report[$monthName]['total_embr_sent'] = $this->printEmbrData
                    ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                    ->sum('embroidery_sent_qty_sum') ?? 0;

            $report[$monthName]['total_embr_rec'] = $this->printEmbrData
                    ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                    ->sum('embroidery_received_qty_sum') ?? 0;

            $report[$monthName]['total_cutting'] = $this->cuttingData
                    ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                    ->sum('cutting_qty_sum') ?? 0;
            $report[$monthName]['total_input'] = $this->inputData
                    ->whereBetween('production_date', [$startOfMonth, $endOfMonth])
                    ->sum('sewing_input_sum') ?? 0;
        }

        return [
            'report' => $report,
            'cuttingFloors' => $cuttingFloors,
            'inputFloors' => $inputFloors,
            'printEmbrFloors' => $printEmbrFloors,
        ];
    }
}
