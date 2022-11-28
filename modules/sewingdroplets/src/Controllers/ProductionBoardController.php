<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\Iedroplets\Models\NextSchedule;
use Carbon\Carbon;

class ProductionBoardController extends Controller
{   

    
    public function productionBoard()
    {
        $date = request()->get('date');
        $date = $date ? $date : Carbon::today()->toDateString();

        $floors = Floor::with('lines')->get()->sortBy('sort');
        $sewingOutputs = $this->sewingOutputsProductionBoardByDate($floors, $date);
        $weeklyInspections = Style::where('inspection_date', date("Y-m-d", strtotime("+1 week")))->get();

        return view('sewingdroplets::reports.production_board_report', [
            'sewing_outputs' => $sewingOutputs['sewing_outputs'],
            'floor_total' => $sewingOutputs['floor_total'],
            'weeklyInspections' => $weeklyInspections,
            'date' => $date, 
        ]);
    }
    
    private function minutesWorked($date, $targets, $now)
    {
        $minutesWorked = $targets->sum('wh') * 60;

        if ($date == $now->toDateString()) {
            $hour = $now->hour + ($now->minute / 60);
            $hoursWorked = ($hour >= 8) ? ($hour - 8) : $hour;
            $minutesWorked = $hoursWorked * 60;
            $lunchMinutes = 0;

            if($now->hour >= 13 && $now->hour < 14){
                $lunchMinutes = $now->minute;
            } elseif ($now->hour > 14) {
                $lunchMinutes = 60;
            }

            $minutesWorked = $minutesWorked - $lunchMinutes;
        }

        return $minutesWorked;
    }

    private function usedMinutes($targets, $minutesWorked)
    {
        $usedMinutes = 0;
        foreach ($targets as $key => $target) {
            if ($minutesWorked <= 0) {
                break;
            }

            $usedMinutes += ($minutesWorked * ($target->operator + $target->helper));
            $minutesWorked -= ($target->wh * 60);
        }

        return $usedMinutes;
    }    

    private function targetedOutput($sewingLineTargets, $minutesWorked)
    {
        $hoursWorked = $minutesWorked / 60;
        $hours = $hoursWorked;

        $targetedOutput = 0;

        foreach ($sewingLineTargets as $key => $sewingLineTarget) {
            if ($hours <= 0) {
                break;
            }

            if ($hours > $sewingLineTarget->wh) {
                $targetedOutput += $sewingLineTarget->wh * $sewingLineTarget->target;
            } else {
                $targetedOutput += $hours * $sewingLineTarget->target;
            }

            $hours -= $sewingLineTarget->wh;
        }

        return $targetedOutput;
    }

    private function sewingManPowner($targets, $minutesWorked)
    {
        $manPower = 0;
        $hours = $minutesWorked / 60;

        foreach ($targets as $key => $target) {
            if ($hours <= 0) {
                break;
            }

            $manPower = $target->operator + $target->helper;

            $hours -= $target->wh;
        }

        return $manPower;
    }

    private function sewingTargetForDay($targets)
    {
        $dayTarget = 0;
        foreach ($targets as $key => $target) {
            $dayTarget += $target->target * $target->wh;
        }

        return $dayTarget;
    }

    private function remarksOnProduction($targets, $minutesWorked)
    {
        $remarks = null;
        $hours = $minutesWorked / 60;

        foreach ($targets as $key => $target) {
            if ($hours <= 0) {
                break;
            }

            $remarks = $target->remarks;

            $hours -= $target->wh;
        }

        return $remarks;
    }

    private function hourlyAvgPlanTarget($planTargets, $targets, $minutesWorked)
    {
        $hoursWorked = $minutesWorked / 60;
        $hours = $hoursWorked;

        $planedProduction = 0;

        foreach ($targets as $key => $target) {
            if ($hours <= 0) {
                break;
            }

            if ($hours > $target['wh']) {
                $planedProduction += ($planTargets[$key]['plan_target'] ?? 0) * $target['wh'];
            } else {
                $planedProduction += ($planTargets[$key]['plan_target'] ?? 0) * $hours;
            }

            $hours -= $target['wh'];
        }

        if ($hoursWorked > 0) {
            return round($planedProduction / $hoursWorked);
        }

        return 0;
    }

    private function initialLineValuesForProductionBoard($floors, $date, $now)
    {
        $outputs = [];

        foreach ($floors as $floor) {
            foreach ($floor->lines->sortBy('sort') as $line) {
                $minutesWorked = $this->minutesWorked($date, $line->sewingTargetsByDate, $now);
                $outputs[$floor->floor_no][$line->line_no] = [
                    'order_count' => 0,
                    'floor' => $floor->floor_no,
                    'line' => $line->line_no,
                    'buyer' => "",
                    'order' => "",
                    'po' => "",             
                    'color' => "",
                    'input_date' => "",
                    'output_finish_date' => "",
                    'inspection_date' => "",                   
                    'hour_0' => 0,
                    'hour_1' => 0,
                    'hour_2' => 0,
                    'hour_3' => 0,
                    'hour_4' => 0,
                    'hour_5' => 0,
                    'hour_6' => 0,
                    'hour_7' => 0,
                    'hour_8' => 0,
                    'hour_9' => 0,
                    'hour_10' => 0,
                    'hour_11' => 0,
                    'hour_12' => 0,
                    'hour_13' => 0,
                    'hour_14' => 0,
                    'hour_15' => 0,
                    'hour_16' => 0,
                    'hour_17' => 0,
                    'hour_18' => 0,
                    'hour_19' => 0,
                    'hour_20' => 0,
                    'hour_21' => 0,
                    'hour_22' => 0,
                    'hour_23' => 0,
                    'total_output' => 0,                   
                    'remarks' => $this->remarksOnProduction($line->sewingTargetsByDate, $minutesWorked),
                    'next_schedule' => NextSchedule::getNextSchedule($line->id)
                ];
            }
        }

        return $outputs;
    }

    private function sewingOutputsProductionBoardByDate($floors, $date)
    {
        Line::$targetDate = $date;
        $now = Carbon::now();

        $sewingOutputs = HourlySewingProductionReport::with([
            'floor',
            'line.sewingTargetsByDate',
            'buyer',
            'order',
            'purchaseOrder',
            'color',
        ])->where('production_date', $date)->orderBy('updated_at', 'asc')->get();

        $outputs = $this->initialLineValuesForProductionBoard($floors, $date, $now);

        foreach ($sewingOutputs as $output) {
            $minutesWorked = $this->minutesWorked($date, $output->line->sewingTargetsByDate, $now);

            $outputs[$output->floor->floor_no][$output->line->line_no] = [
                'order_count' => $outputs[$output->floor->floor_no][$output->line->line_no]['order_count'] + 1,
                'floor' => $output->floor->floor_no,
                'line' => $output->line->line_no,
                'buyer' => $output->buyer->name,
                'order' => $output->order->order_style_no,               
                'po' => $output->purchaseOrder->po_no,
                'color' => $output->color->name,
                'input_date' => CuttingInventoryChallan::getFirstInpurDate($output->order_id, $output->color_id),
                'output_finish_date' => date('Y-m-d'),
                'inspection_date' => date('Y-m-d'),
                'hour_0' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_0'] + $output->hour_0,
                'hour_1' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_1'] + $output->hour_1,
                'hour_2' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_2'] + $output->hour_2,
                'hour_3' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_3'] + $output->hour_3,
                'hour_4' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_4'] + $output->hour_4,
                'hour_5' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_5'] + $output->hour_5,
                'hour_6' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_6'] + $output->hour_6,
                'hour_7' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_7'] + $output->hour_7,
                'hour_8' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_8'] + $output->hour_8,
                'hour_9' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_9'] + $output->hour_9,
                'hour_10' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_10'] + $output->hour_10,
                'hour_11' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_11'] + $output->hour_11,
                'hour_12' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_12'] + $output->hour_12,
                'hour_13' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_13'] + $output->hour_13,
                'hour_14' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_14'] + $output->hour_14,
                'hour_15' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_15'] + $output->hour_15,
                'hour_16' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_16'] + $output->hour_16,
                'hour_17' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_17'] + $output->hour_17,
                'hour_18' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_18'] + $output->hour_18,
                'hour_19' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_19'] + $output->hour_19,
                'hour_20' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_20'] + $output->hour_20,
                'hour_21' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_21'] + $output->hour_21,
                'hour_22' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_22'] + $output->hour_22,
                'hour_23' => $outputs[$output->floor->floor_no][$output->line->line_no]['hour_23'] + $output->hour_23,
                'total_output' => $outputs[$output->floor->floor_no][$output->line->line_no]['total_output']
                    + $output->total_output,
                'remarks' => $this->remarksOnProduction($output->line->sewingTargetsByDate, $minutesWorked),
                'next_schedule' => NextSchedule::getNextSchedule($output->line_id),
            ];
        }

        return [
            'sewing_outputs' => $outputs,
            'floor_total' => $this->floorOutputs($floors, $outputs)
        ];
    }

}
