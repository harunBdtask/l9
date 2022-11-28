<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Services;

use Carbon\Carbon;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport;
use SkylarkSoft\GoRMG\Iedroplets\Models\InspectionSchedule;
use SkylarkSoft\GoRMG\Iedroplets\Models\NextSchedule;
use SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class LineWiseOutputService
{
    public function sewingOutputsByDate($floors, $date)
    {
        Line::$targetDate = $date;
        $now = Carbon::now();
        $query = HourlySewingProductionReport::query();
        $query->when(request('factory_id') != null, function ($q) {
            return $q->where('factory_id', request('factory_id'));
        });
        $query->when(factoryId() != null, function ($q) {
            return $q->where('factory_id', factoryId());
        });

        $sewing_starting_hour_query = GarmentsProductionEntry::query()->where('factory_id', factoryid())->first();
        $sewing_starting_hour = $sewing_starting_hour_query ? $sewing_starting_hour_query->sewing_starting_hour : 8;
        $sewingOutputs = $query->with([
            'floor:id,floor_no',
            'line.sewingTargetsByDate',
            'buyer:id,name',
            'order:id,style_name,smv,item_details',
            'purchaseOrder:id,po_no',
            'color:id,name',
        ])
            ->where('production_date', $date)
            ->orderBy('updated_at', 'asc')
            ->get();

        $outputs = $this->initialLineValues($floors, $date, $now);

        foreach ($sewingOutputs as $output) {
            $order_id = $output->order_id;
            $garments_item_id = $output->garments_item_id;
            $smv = Order::getOrderItemWiseFactorySMV($order_id, $garments_item_id) ?? Order::getOrderItemWiseSMV($order_id, $garments_item_id) ?? $output->order->smv ?? 0;
            $minutesWorked = $this->minutesWorked($date, $output->line->sewingTargetsByDate, $now);

            $outputs[$output->floor->floor_no][$output->line->line_no] = [
                'order_count' => $outputs[$output->floor->floor_no][$output->line->line_no]['order_count'] + 1,
                'floor_id' => $output->floor->id,
                'line_id' => $output->line->id,
                'floor' => $output->floor->floor_no ?? 'N/A',
                'line' => $output->line->line_no ?? 'N/A',
                'buyer' => $output->buyer->name ?? 'N/A',
                'order' => $output->order->style_name ?? 'N/A',
                'item' => $output->garmentsItem->name ?? 'N/A',
                'po' => $output->purchaseOrder->po_no ?? 'N/A',
                'color' => $output->color->name ?? 'N/A',
                'smv' => $smv ?? 0,
                'hours_worked' => ($minutesWorked / 60),
                'mp' => $this->sewingManPowner($output->line->sewingTargetsByDate, $minutesWorked),
                //'plan_target' => $output->purchaseOrder->plan_target,
                'hourly_target' => $this->hourlySewingTarget($output->line->sewingTargetsByDate, $minutesWorked),
                'day_target' => $this->sewingTargetForDay($output->line->sewingTargetsByDate),
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
                'targeted_output' => $this->targetedOutput($output->line->sewingTargetsByDate, $minutesWorked),
                'sewing_rejection' => $outputs[$output->floor->floor_no][$output->line->line_no]['sewing_rejection']
                    + $output->sewing_rejection,
                'production_minutes' => $outputs[$output->floor->floor_no][$output->line->line_no]['production_minutes']
                    + ($output->total_output * $smv),
                'used_minutes' => $this->getAvailableMinutes($output->line->sewingTargetsByDate, $sewing_starting_hour),
                'remarks' => $this->remarksOnProduction($output->line->sewingTargetsByDate, $minutesWorked),
            ];
            /*$outputs[$output->floor->floor_no][$output->line->line_no]['all_plan_targets'][] = [
                'plan_target' => $output->purchaseOrder->plan_target,
                'order_id' => $output->order_id,
            ];*/

            if ($outputs[$output->floor->floor_no][$output->line->line_no]['used_minutes'] > 0) {
                $outputs[$output->floor->floor_no][$output->line->line_no]['line_efficiency'] =
                    $outputs[$output->floor->floor_no][$output->line->line_no]['production_minutes']
                    / $outputs[$output->floor->floor_no][$output->line->line_no]['used_minutes']
                    * 100;
            } else {
                $outputs[$output->floor->floor_no][$output->line->line_no]['line_efficiency'] = 0;
            }

            if ($minutesWorked) {
                $outputs[$output->floor->floor_no][$output->line->line_no]['hourly_avg_production'] = round(
                    $outputs[$output->floor->floor_no][$output->line->line_no]['total_output']
                    / ($minutesWorked / 60)
                );
            } else {
                $outputs[$output->floor->floor_no][$output->line->line_no]['hourly_avg_production'] = 0;
            }

            /*$outputs[$output->floor->floor_no][$output->line->line_no]['hourly_avg_plan_target'] = $this->hourlyAvgPlanTarget(
                $outputs[$output->floor->floor_no][$output->line->line_no]['all_plan_targets'],
                $output->line->sewingTargetsByDate,
                $minutesWorked
            );

            if ($outputs[$output->floor->floor_no][$output->line->line_no]['hourly_avg_plan_target'] > 0) {
                $outputs[$output->floor->floor_no][$output->line->line_no]['production_efficiency'] =
                    $outputs[$output->floor->floor_no][$output->line->line_no]['hourly_avg_production']
                    / $outputs[$output->floor->floor_no][$output->line->line_no]['hourly_avg_plan_target']
                    * 100;
            } else {
                $outputs[$output->floor->floor_no][$output->line->line_no]['production_efficiency'] = 0;
            }*/
        }

        // add total row each floor
        $floor_total = $this->floorOutputs($floors, $outputs);
        foreach ($outputs as $floor_no_key => $value) {
            $outputs[$floor_no_key]['total_row'] = $floor_total[$floor_no_key];
        }

        return [
            'sewing_outputs' => $outputs,
            'floor_total' => $floor_total
        ];
    }

    public function initialLineValues($floors, $date, $now)
    {
        $outputs = [];

        foreach ($floors as $floor) {
            foreach ($floor->lines->sortBy('sort') as $line) {
                $minutesWorked = $this->minutesWorked($date, $line->sewingTargetsByDate, $now);
                $outputs[$floor->floor_no][$line->line_no] = [
                    'order_count' => 0,
                    'floor_id' => $floor->id,
                    'line_id' => $line->id,
                    'floor' => $floor->floor_no,
                    'line' => $line->line_no,
                    'buyer' => "",
                    'order' => "",
                    'item' => "",
                    'po' => "",
                    'color' => "",
                    'smv' => 0,
                    'hours_worked' => 0,
                    'mp' => $this->sewingManPowner($line->sewingTargetsByDate, $minutesWorked),
                    // 'plan_target' => 0,
                    'total_plan_target' => 0,
                    'hourly_target' => $this->hourlySewingTarget($line->sewingTargetsByDate, $minutesWorked),
                    'day_target' => $this->sewingTargetForDay($line->sewingTargetsByDate),
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
                    'targeted_output' => 0,
                    'sewing_rejection' => 0,
                    'production_minutes' => 0,
                    'used_minutes' => 0,
                    'remarks' => $this->remarksOnProduction($line->sewingTargetsByDate, $minutesWorked),
                    'line_efficiency' => 0,
                    'hourly_avg_production' => 0,
                    //  'hourly_avg_plan_target' => 0,
                    //  'production_efficiency' => 0
                ];
            }
        }

        return $outputs;
    }

    public function floorOutputs($floors, $sewingOutputs)
    {
        $floorOutputs = [];

        foreach ($floors as $floor) {
            $lineOutputCollection = collect($sewingOutputs[$floor->floor_no] ?? []);

            $floorOutputs[$floor->floor_no] = [
                'floor_no' => $floor->floor_no,
                'total_plan_target' => $lineOutputCollection->sum('plan_target'),
                'total_hourly_target' => $lineOutputCollection->sum('hourly_target'),
                'total_day_target' => $lineOutputCollection->sum('day_target'),
                'hour_0' => $lineOutputCollection->sum('hour_0'),
                'hour_1' => $lineOutputCollection->sum('hour_1'),
                'hour_2' => $lineOutputCollection->sum('hour_2'),
                'hour_3' => $lineOutputCollection->sum('hour_3'),
                'hour_4' => $lineOutputCollection->sum('hour_4'),
                'hour_5' => $lineOutputCollection->sum('hour_5'),
                'hour_6' => $lineOutputCollection->sum('hour_6'),
                'hour_7' => $lineOutputCollection->sum('hour_7'),
                'hour_8' => $lineOutputCollection->sum('hour_8'),
                'hour_9' => $lineOutputCollection->sum('hour_9'),
                'hour_10' => $lineOutputCollection->sum('hour_10'),
                'hour_11' => $lineOutputCollection->sum('hour_11'),
                'hour_12' => $lineOutputCollection->sum('hour_12'),
                'hour_13' => $lineOutputCollection->sum('hour_13'),
                'hour_14' => $lineOutputCollection->sum('hour_14'),
                'hour_15' => $lineOutputCollection->sum('hour_15'),
                'hour_16' => $lineOutputCollection->sum('hour_16'),
                'hour_17' => $lineOutputCollection->sum('hour_17'),
                'hour_18' => $lineOutputCollection->sum('hour_18'),
                'hour_19' => $lineOutputCollection->sum('hour_19'),
                'hour_20' => $lineOutputCollection->sum('hour_20'),
                'hour_21' => $lineOutputCollection->sum('hour_21'),
                'hour_22' => $lineOutputCollection->sum('hour_22'),
                'hour_23' => $lineOutputCollection->sum('hour_23'),
                'total_output' => $lineOutputCollection->sum('total_output'),
                'targeted_output' => $lineOutputCollection->sum('targeted_output'),
                'total_sewing_rejection' => $lineOutputCollection->sum('sewing_rejection'),
                'total_production_minutes' => $lineOutputCollection->sum('production_minutes'),
                'total_used_minutes' => $lineOutputCollection->sum('used_minutes'),
                'floor_efficiency' => 0,
                'total_hourly_avg_production' => $lineOutputCollection->sum('hourly_avg_production'),
                // 'total_hourly_avg_plan_target' => $lineOutputCollection->sum('hourly_avg_plan_target'),
                // 'floor_production_efficiency' => 0
            ];

            $floorOutputs[$floor->floor_no]['floor_efficiency'] = 0;
            if ($floorOutputs[$floor->floor_no]['total_used_minutes'] > 0) {
                $floorOutputs[$floor->floor_no]['floor_efficiency'] = $floorOutputs[$floor->floor_no]['total_production_minutes']
                    / $floorOutputs[$floor->floor_no]['total_used_minutes']
                    * 100;
            }

            /*$floorOutputs[$floor->floor_no]['floor_production_efficiency'] = 0;
            if ($floorOutputs[$floor->floor_no]['total_hourly_avg_plan_target'] > 0) {
                $floorOutputs[$floor->floor_no]['floor_production_efficiency'] = $floorOutputs[$floor->floor_no]['total_hourly_avg_production']
                    / $floorOutputs[$floor->floor_no]['total_hourly_avg_plan_target']
                    * 100;
            }*/

            $floorOutputs[$floor->floor_no]['achievment'] = 0;
            if ($floorOutputs[$floor->floor_no]['targeted_output'] > 0) {
                $floorOutputs[$floor->floor_no]['achievment'] = $floorOutputs[$floor->floor_no]['total_output']
                    / $floorOutputs[$floor->floor_no]['targeted_output']
                    * 100;
            }
        }

        return $floorOutputs;
    }

    public function minutesWorked($date, $targets, $now)
    {
        $minutesWorked = $targets->sum('wh') * 60;

        if ($date == $now->toDateString()) {
            $hour = $now->hour + ($now->minute / 60);
            $hoursWorked = ($hour >= 8) ? ($hour - 8) : $hour;
            $minutesWorked = $hoursWorked * 60;
            $lunchMinutes = 0;

            if ($now->hour >= 13 && $now->hour < 14) {
                $lunchMinutes = $now->minute;
            } elseif ($now->hour >= 14) {
                $lunchMinutes = 60;
            }

            $minutesWorked = $minutesWorked - $lunchMinutes;
        }

        return $minutesWorked;
    }

    public function getAvailableMinutes($sewingTargetsByDate, $sewing_starting_hour = 8)
    {
        $available_minutes = 0;
        if ($sewingTargetsByDate->count()) {
            $now = Carbon::now();
            $start_wh = $sewing_starting_hour;
            $end_wh = 0;
            $total_hourly_target = 0;
            $available_minutes = 0;
            $current_date = date('Y-m-d');
            $current_hour = (int)date('H');
            $current_minute = $now->minute;
            foreach ($sewingTargetsByDate as $sewing_target) {
                $target_date = date('Y-m-d', strtotime($sewing_target->target_date));
                $end_wh += $start_wh + $sewing_target->wh;
                $completed_work_minutes = 0;
                for ($i = $start_wh; $i < $end_wh; $i++) {
                    if ($current_date < $target_date) {
                        continue;
                    } elseif ($current_date == $target_date && $i > $current_hour) {
                        continue;
                    }
                    if ($i == 13) {
                        $end_wh++;
                        $completed_work_minutes += 0;
                        continue;
                    }
                    if ($current_date == $target_date && $i == $current_hour) {
                        $completed_work_minutes += $current_minute;
                    } else {
                        $completed_work_minutes += 60;
                    }
                    $total_hourly_target += $sewing_target->target;
                }
                $start_wh += $end_wh;
                if ($current_date < $target_date) {
                    continue;
                }
                $completed_work_hour = $completed_work_minutes / 60;
                if ($completed_work_hour >= $sewing_target->wh) {
                    $completed_work_hour = $sewing_target->wh;
                }
                $available_minutes += ($sewing_target->operator + $sewing_target->helper) * $completed_work_hour * 60;
            }
        }
        return $available_minutes;
    }

    public function usedMinutes($targets, $minutesWorked)
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

    public function hourlySewingTarget($targets, $minutesWorked)
    {
        $hourlyTarget = 0;
        $hours = $minutesWorked / 60;

        foreach ($targets as $key => $target) {
            if ($hours <= 0) {
                break;
            }

            $hourlyTarget = $target->target;

            $hours -= $target->wh;
        }

        return $hourlyTarget;
    }

    public function targetedOutput($sewingLineTargets, $minutesWorked)
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

    public function sewingManPowner($targets, $minutesWorked)
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

    public function sewingTargetForDay($targets)
    {
        $dayTarget = 0;
        foreach ($targets as $key => $target) {
            $dayTarget += $target->target * $target->wh;
        }

        return $dayTarget;
    }

    public function remarksOnProduction($targets, $minutesWorked)
    {
        $remarks = '';
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

    public function hourlyAvgPlanTarget($planTargets, $targets, $minutesWorked)
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

    public function initialLineValuesForProductionBoard($floors, $date, $now)
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
                    'booking_no' => "",
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

    public function sewingOutputsProductionBoardByDate($floors, $date)
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
                'floor' => $output->floor->floor_no ?? 'N/A',
                'line' => $output->line->line_no ?? 'N/A',
                'buyer' => $output->buyer->name ?? 'N/A',
                'booking_no' => $output->order->booking_no ?? 'N/A',
                'order' => $output->order->order_style_no ?? 'N/A',
                'po' => $output->purchaseOrder->po_no ?? 'N/A',
                'color' => $output->color->name ?? 'N/A',
                'input_date' => CuttingInventoryChallan::getFirstInpurDate($output->order_id, $output->color_id),
                'output_finish_date' => $output->line->inspectionSchedule->output_finish_date ?? '',
                'inspection_date' => $this->getInspectionScheduleDate($output->style->id ?? null),
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

    public function forecastSewingData(&$sewingOutputs, $requestDate)
    {
        foreach ($sewingOutputs['sewing_outputs'] as $floor => $floorVal) {
            foreach($floorVal as $line => $lineVal) {
                if(array_key_exists('line_id', $lineVal)){
                    $todayTargetQuery = SewingLineTarget::query()
                        ->where('target_date', $requestDate)
                        ->where('line_id', $lineVal['line_id'])
                        ->first();
                    $todayTarget = 0;
                    $todayWh = 0;
                    if ($todayTargetQuery) {
                        $todayWh = $todayTargetQuery->wh;
                        $todayTarget = $todayTargetQuery->target * $todayTargetQuery->wh;
                    }
                    $todayData = HourlySewingProductionReport::query()
                        ->selectRaw('buyer_id, order_id, garments_item_id, sum(hour_0 + hour_1 + hour_2 + hour_3 + hour_4 + hour_5 + hour_6 + hour_7 + hour_8 + hour_9 + hour_10 + hour_11 + hour_12 + hour_13 + hour_14 + hour_15 + hour_16 + hour_17 + hour_18 + hour_19 + hour_20 + hour_21 + hour_22 + hour_23) as sewing_production')
                        ->where('production_date', $requestDate)
                        ->where('line_id', $lineVal['line_id'])
                        ->groupBy('buyer_id', 'order_id', 'garments_item_id')
                        ->get()
                        ->map(function($item) use ($requestDate, $lineVal) {
                            $order_id = $item->order_id;
                            $garments_item_id = $item->garments_item_id;
                            $smv = Order::getOrderItemWiseFactorySMV($order_id, $garments_item_id) ?? Order::getOrderItemWiseSMV($order_id, $garments_item_id) ?? 0;
                            $firstInputDateQuery = FinishingProductionReport::where([
                                'order_id' => $order_id
                            ])
                                ->where('sewing_input', '>', 0)
                                ->first();
                            $firstInputDate = $firstInputDateQuery ? $firstInputDateQuery->production_date : null;
                            $noDaysOutput = 0;
                            if ($firstInputDate) {
                                $firstInputDateCarbon = Carbon::parse($firstInputDate);
                                $curDate = Carbon::parse($requestDate);
                                $noDaysOutput = $firstInputDateCarbon->diffInDays($curDate);
                            }
                            $totalDataQuery = TotalProductionReport::query()->where([
                                'order_id' => $order_id,
                                'garments_item_id' => $garments_item_id,
                            ]);
                            $todaysLineTotalInput = FinishingProductionReport::query()
                                ->where('sewing_input', '>', 0)
                                ->where([
                                    'production_date' => $requestDate,
                                    'line_id' => $lineVal['line_id'],
                                ]);

                            $todayInput = $totalDataQuery->sum('todays_input');
                            $totalInput = $todaysLineTotalInput->sum('sewing_input');
                            $totalProduction = $item->sewing_production ?? 0;
                            return [
                                'buyer_id' => $item->buyer_id,
                                'buyer' => $item->buyer,
                                'order_id' => $item->order_id,
                                'order' => $item->order,
                                'garments_item_id' => $item->garments_item_id,
                                'garmentsItem' => $item->garmentsItem,
                                'smv' => $smv,
                                'order_qty' => $item->order->pq_qty_sum,
                                'first_input_date' => $firstInputDate,
                                'no_days_output' => $noDaysOutput,
                                'today_input' => $todayInput,
                                'total_input' => $totalInput,
                                'total_output' => $totalProduction,
                            ];
                        })->toArray();
                    $sewingOutputs['sewing_outputs'][$floor][$line]['today_data'] = $todayData;
                    $sewingOutputs['sewing_outputs'][$floor][$line]['today_target'] = $todayTarget;
                    $sewingOutputs['sewing_outputs'][$floor][$line]['today_wh'] = $todayWh;
                }

            }
        }
    }

    public function getInspectionScheduleDate($style_id)
    {
        if ($style_id) {
            $data = InspectionSchedule::where('style_id', $style_id)
                ->orderBy('inspection_date', 'desc')
                ->first();
        }
        return $data->inspection_date ?? '';
    }
}
