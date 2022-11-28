<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Finishingdroplets\Models\HourWiseFinishingProduction;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;

class MonthlyTotalReceivedFinishingReportService
{
    protected $month;
    protected $startDate;
    protected $endDate;
    protected $dateRange;

    public function init($month): MonthlyTotalReceivedFinishingReportService
    {
        $this->month = $month;
        $this->startDate = Carbon::make($this->month)->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::make($this->month)->endOfMonth()->format('Y-m-d');
        $this->dateRange = CarbonPeriod::create($this->startDate, $this->endDate);

        return $this;
    }

    public function generate(): array
    {

        $floorSumQueries = ['SUM(sewing_output) AS sewing_output_sum'];
        $floorSumQueriesString = implode(',', $floorSumQueries);

        $floorReceivedData = FinishingProductionReport::query()
            ->with(['floor'])
            ->select('production_date', 'floor_id', DB::raw($floorSumQueriesString))
            ->whereDate('production_date', '>=', $this->startDate)
            ->whereDate('production_date', '<=', $this->endDate)
            ->groupBy('production_date', 'floor_id')
            ->get();

        $receiveFloors = $floorReceivedData->unique('floor_id')->pluck('floor');

        $finishingData = HourWiseFinishingProduction::query()
            ->with(['floor'])
            ->selectRaw(DB::raw('*,SUM(hour_0+hour_1+hour_2+hour_3+hour_4+hour_5+hour_6+hour_7+hour_8+hour_9+hour_10+hour_11+hour_12+hour_13+hour_14+hour_15+hour_16+hour_17+hour_18+hour_19+hour_20+hour_21+hour_22+hour_23) AS total_hour_production'))
            ->where('production_type', 'packing')
            ->whereDate('production_date', '>=', $this->startDate)
            ->whereDate('production_date', '<=', $this->endDate)
            ->groupBy('production_date', 'finishing_floor_id')
            ->get();

        $finishingFloors = $finishingData->unique('finishing_floor_id')->pluck('floor');

        $formatData = $this->format($floorReceivedData, $receiveFloors, $finishingData, $finishingFloors);

        return ['reportData' => $formatData, 'receiveFloors' => $receiveFloors, 'finishingFloors' => $finishingFloors];
    }

    public function format($floorReceivedData, $receiveFloors, $finishingData, $finishingFloors): array
    {
        $formatted = [];

        foreach ($this->dateRange as $date) {
            $date = $date->format('Y-m-d');

            foreach ($receiveFloors as $receiveFloor) {
                $sewingOutputSum = collect($floorReceivedData)
                        ->where('production_date', $date)
                        ->where('floor_id', $receiveFloor->id)
                        ->first()['sewing_output_sum'] ?? 0;

                $formatted[$date][$receiveFloor->floor_no] = $sewingOutputSum;
            }

            foreach ($finishingFloors as $finishingFloor) {
                $totalHourProduction = collect($finishingData)
                        ->where('production_date', $date)
                        ->where('finishing_floor_id', $finishingFloor->id)
                        ->first()['total_hour_production'] ?? 0;
                $formatted[$date][$finishingFloor->name] = $totalHourProduction;
            }

            $totalSewingOutput = collect($floorReceivedData)->where('production_date', $date)->sum('sewing_output_sum');
            $totalFinishing = collect($finishingData)->where('production_date', $date)->sum('total_hour_production');

            $formatted[$date]['total_sewing_output'] = $totalSewingOutput;
            $formatted[$date]['total_finishing'] = $totalFinishing;
        }

        return $formatted;
    }
}
