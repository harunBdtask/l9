<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReportView;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use Carbon\Carbon, Session;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;

class ProductionDashboardController extends Controller
{

    private function getproductionDashboardData()
    {
        $date = Carbon::today()->toDateString();
        $query = Floor::query();
        $query->when(request('factory_id') != null, function ($q) {
            return $q->where('factory_id', request('factory_id'))->orderBy('sort', 'asc');
        });
        $query->when(factoryId() != null, function ($q) {
            return $q->where('factory_id', factoryId());
        });
        $floors = $query->with('lines:id,line_no,floor_id,sort')->get()->sortBy('sort');

        $sewingOutputs = $this->sewingOutputsByDate($floors, $date);
        $total_sewing_target = 0;
        $total_sewing_production = 0;
        $total_available_minutes = 0;
        $total_earned_minutes = 0;

        foreach ($sewingOutputs['floor_total'] as $floorNo => $floor) {
            $data['achievment'][$floorNo] = $floor['achievment'];
            $data['floorEfficiency'][$floorNo] = $floor['floor_efficiency'];
            $data['output'][$floorNo] = [
                'output' => $floor['total_output'],
                'target' => $floor['targeted_output'],
            ];
            $total_sewing_target += $floor['targeted_output'];
            $total_sewing_production += $floor['total_output'];
            $total_available_minutes += $floor['total_production_minutes'];
            $total_earned_minutes += $floor['total_used_minutes'];
        }

        // only for auto email email
        $total_production_data = [
            'total_sewing_target' => (int) $total_sewing_target,
            'total_sewing_production' => $total_sewing_production,
            'total_available_minutes' => $total_available_minutes,
            'total_earned_minutes' => $total_earned_minutes,
            'total_efficiency' => ($total_earned_minutes > 0) ? number_format($total_available_minutes * 100 / $total_earned_minutes, 2) : 0
        ];
        Session::put('total_production_data', $total_production_data);
        // end auto email area

        $floorNo = request()->get('floor_no');
        if (array_key_exists($floorNo, $sewingOutputs['sewing_outputs'])) {
            $sewingOutputsByLine = $sewingOutputs['sewing_outputs'][$floorNo];

            foreach ($sewingOutputsByLine as $lineNo => $sewingOutput) {
                $data['lineTarget'][$floorNo][$lineNo] = [
                    'output' => $sewingOutput['total_output'],
                    'target' => $sewingOutput['targeted_output'],
                    'efficiency' => $sewingOutput['line_efficiency'] ?? 0
                ];
            }
            $data['floorLinesHourlyData'][$floorNo] = $sewingOutputs['sewing_outputs'][$floorNo];
        } else {
            foreach ($sewingOutputs['sewing_outputs'] as $floorNo => $sewingOutputsByLine) {
                foreach ($sewingOutputsByLine as $lineNo => $sewingOutput) {
                    $data['lineTarget'][$floorNo][$lineNo] = [
                        'output' => $sewingOutput['total_output'],
                        'target' => $sewingOutput['targeted_output'],
                        'efficiency' => $sewingOutput['line_efficiency'] ?? 0
                    ];
                }
            }
            $data['floorLinesHourlyData'] = $sewingOutputs['sewing_outputs'];
        }

        foreach ($floors as $floor) {
            $lastDaySummary = $floor->sewingSummaryForLastProductiveDay();
            $floor->last_day_ouptut = $lastDaySummary['output'];
            $floor->last_day_target = $lastDaySummary['target'];
        }

        return [
            'data' => $data,
            'floors' => $floors
        ];
    }

    public function productionDashboard()
    {
        return view('sewingdroplets::pages.production_dashboard', $this->getproductionDashboardData());
    }

    public function productionDashboardV2()
    {
        return view('sewingdroplets::pages.production_dashboard_v2', $this->getproductionDashboardData());
    }

    public function productionDashboardV3()
    {
        return view('sewingdroplets::pages.production_dashboard_v3', $this->getproductionDashboardData());
    }

    private function getproductionDashboardDataByFloor()
    {
        $date = Carbon::today()->toDateString();
        $query = Floor::query();
        $query->when(request('factory_id') != null, function ($q) {
            return $q->where('factory_id', request('factory_id'))->orderBy('sort', 'asc');
        });
        $query->when(factoryId() != null, function ($q) {
            return $q->where('factory_id', factoryId());
        });
        $floors = $query->with('lines:id,line_no,floor_id,sort')->get()->sortBy('sort');

        $sewingOutputs = $this->sewingOutputsByDate($floors, $date);
        $total_sewing_target = 0;
        $total_sewing_production = 0;
        $total_available_minutes = 0;
        $total_earned_minutes = 0;

        foreach ($sewingOutputs['floor_total'] as $floorNo => $floor) {
            $data['achievment'][$floorNo] = $floor['achievment'];
            $data['floorEfficiency'][$floorNo] = $floor['floor_efficiency'];
            $data['output'][$floorNo] = [
                'output' => $floor['total_output'],
                'target' => $floor['targeted_output'],
            ];
            $total_sewing_target += $floor['targeted_output'];
            $total_sewing_production += $floor['total_output'];
            $total_available_minutes += $floor['total_production_minutes'];
            $total_earned_minutes += $floor['total_used_minutes'];
        }

        // only for auto email email
        $total_production_data = [
            'total_sewing_target' => (int) $total_sewing_target,
            'total_sewing_production' => $total_sewing_production,
            'total_available_minutes' => $total_available_minutes,
            'total_earned_minutes' => $total_earned_minutes,
            'total_efficiency' => ($total_earned_minutes > 0) ? number_format($total_available_minutes * 100 / $total_earned_minutes, 2) : 0
        ];
        Session::put('total_production_data', $total_production_data);
        // end auto email area

        $floorNo = request()->get('floor_no');
        $lineNo = request()->get('line_no');
        if (array_key_exists($floorNo, $sewingOutputs['sewing_outputs'])) {
            $sewingOutputsByLine = $sewingOutputs['sewing_outputs'][$floorNo];
            if (array_key_exists($lineNo, $sewingOutputsByLine)) {
                $sewingOutput = $sewingOutputsByLine[$lineNo];
                $data['lineTarget'][$floorNo][$lineNo] = [
                    'output' => $sewingOutput['total_output'],
                    'target' => $sewingOutput['targeted_output'],
                    'efficiency' => $sewingOutput['line_efficiency'] ?? 0
                ];
                $data['floorLinesHourlyData'][$floorNo][$lineNo] = $sewingOutputs['sewing_outputs'][$floorNo][$lineNo];
            } else {
                foreach ($sewingOutputsByLine as $lineNo => $sewingOutput) {
                    $data['lineTarget'][$floorNo][$lineNo] = [
                        'output' => $sewingOutput['total_output'],
                        'target' => $sewingOutput['targeted_output'],
                        'efficiency' => $sewingOutput['line_efficiency'] ?? 0
                    ];
                }
                $data['floorLinesHourlyData'][$floorNo] = $sewingOutputs['sewing_outputs'][$floorNo];
            }
            
        } else {
            foreach ($sewingOutputs['sewing_outputs'] as $floorNo => $sewingOutputsByLine) {
                foreach ($sewingOutputsByLine as $lineNo => $sewingOutput) {
                    $data['lineTarget'][$floorNo][$lineNo] = [
                        'output' => $sewingOutput['total_output'],
                        'target' => $sewingOutput['targeted_output'],
                        'efficiency' => $sewingOutput['line_efficiency'] ?? 0
                    ];
                }
            }
            $data['floorLinesHourlyData'] = $sewingOutputs['sewing_outputs'];
        }

        foreach ($floors as $floor) {
            $lastDaySummary = $floor->sewingSummaryForLastProductiveDay();
            $floor->last_day_ouptut = $lastDaySummary['output'];
            $floor->last_day_target = $lastDaySummary['target'];
        }

        return [
            'data' => $data,
            'floors' => $floors
        ];
    }

    public function productionDashboardV4()
    {
        return view('sewingdroplets::pages.production_dashboard_v4', $this->getproductionDashboardDataByFloor());
    }

    public function productionDashboardV5()
    {
        return view('sewingdroplets::pages.production_dashboard_v5', $this->getproductionDashboardDataByFloor());
    }

    private function sewingOutputsByDate($floors, $date)
    {
        Line::$targetDate = $date;
        $now = Carbon::now();

        $query = HourlySewingProductionReportView::query();
        $query->when(request('factory_id') != null, function ($q) {
            return $q->where('factory_id', request('factory_id'));
        });
        $query->when(factoryId() != null, function ($q) {
            return $q->where('factory_id', factoryId());
        });

        $sewing_starting_hour_query = GarmentsProductionEntry::query()->where('factory_id', factoryId())->first();
        $sewing_starting_hour = $sewing_starting_hour_query ? $sewing_starting_hour_query->sewing_starting_hour : 8;
        $sewingOutputs = $query->with('line.sewingTargetsByDate')
            ->where('production_date', $date)
            ->orderBy('updated_at', 'asc')
            ->get();

        $outputs = $this->initialLineValues($floors, $date, $now);

        foreach ($sewingOutputs as $output) {
            $order_id = $output->order_id;
            $garments_item_id = $output->garments_item_id;
            $smv = Order::getOrderItemWiseFactorySMV($order_id, $garments_item_id) ?? Order::getOrderItemWiseSMV($order_id, $garments_item_id) ?? $output->order->smv ?? 0;
            $minutesWorked = $this->minutesWorked($date, $output->line->sewingTargetsByDate, $now);

            $outputs[$output->floor][$output->line_no] = [
                'order_count' => $outputs[$output->floor][$output->line_no]['order_count'] + 1,
                'floor_id' => $output->floor_id ?? null,
                'floor' => $output->floor ?? 'N/A',
                'line_id' => $output->line_id ?? null,
                'line' => $output->line_no ?? 'N/A',
                'buyer' => $output->buyer ?? 'N/A',
                'order' => $output->order_no ?? 'N/A',
                'item' => $output->garmentsItem->name ?? 'N/A',
                'po' => $output->po ?? 'N/A',
                'color' => $output->color ?? 'N/A',
                'smv' => $smv ?? 0,
                'hours_worked' => ($minutesWorked / 60),
                'mp' => $this->sewingManPowner($output->line->sewingTargetsByDate, $minutesWorked),
                'wh' => $this->sewingTargetWh($output->line->sewingTargetsByDate),
                'hourly_target' => $this->hourlySewingTarget($output->line->sewingTargetsByDate, $minutesWorked),
                'day_target' => $this->sewingTargetForDay($output->line->sewingTargetsByDate),
                'hour_0' => $outputs[$output->floor][$output->line_no]['hour_0'] + $output->hour_0,
                'hour_1' => $outputs[$output->floor][$output->line_no]['hour_1'] + $output->hour_1,
                'hour_2' => $outputs[$output->floor][$output->line_no]['hour_2'] + $output->hour_2,
                'hour_3' => $outputs[$output->floor][$output->line_no]['hour_3'] + $output->hour_3,
                'hour_4' => $outputs[$output->floor][$output->line_no]['hour_4'] + $output->hour_4,
                'hour_5' => $outputs[$output->floor][$output->line_no]['hour_5'] + $output->hour_5,
                'hour_6' => $outputs[$output->floor][$output->line_no]['hour_6'] + $output->hour_6,
                'hour_7' => $outputs[$output->floor][$output->line_no]['hour_7'] + $output->hour_7,
                'hour_8' => $outputs[$output->floor][$output->line_no]['hour_8'] + $output->hour_8,
                'hour_9' => $outputs[$output->floor][$output->line_no]['hour_9'] + $output->hour_9,
                'hour_10' => $outputs[$output->floor][$output->line_no]['hour_10'] + $output->hour_10,
                'hour_11' => $outputs[$output->floor][$output->line_no]['hour_11'] + $output->hour_11,
                'hour_12' => $outputs[$output->floor][$output->line_no]['hour_12'] + $output->hour_12,
                'hour_13' => $outputs[$output->floor][$output->line_no]['hour_13'] + $output->hour_13,
                'hour_14' => $outputs[$output->floor][$output->line_no]['hour_14'] + $output->hour_14,
                'hour_15' => $outputs[$output->floor][$output->line_no]['hour_15'] + $output->hour_15,
                'hour_16' => $outputs[$output->floor][$output->line_no]['hour_16'] + $output->hour_16,
                'hour_17' => $outputs[$output->floor][$output->line_no]['hour_17'] + $output->hour_17,
                'hour_18' => $outputs[$output->floor][$output->line_no]['hour_18'] + $output->hour_18,
                'hour_19' => $outputs[$output->floor][$output->line_no]['hour_19'] + $output->hour_19,
                'hour_20' => $outputs[$output->floor][$output->line_no]['hour_20'] + $output->hour_20,
                'hour_21' => $outputs[$output->floor][$output->line_no]['hour_21'] + $output->hour_21,
                'hour_22' => $outputs[$output->floor][$output->line_no]['hour_22'] + $output->hour_22,
                'hour_23' => $outputs[$output->floor][$output->line_no]['hour_23'] + $output->hour_23,
                'total_output' => $outputs[$output->floor][$output->line_no]['total_output']
                    + $output->total_output,
                'targeted_output' => $this->targetedOutput($output->line->sewingTargetsByDate, $minutesWorked),
                'sewing_rejection' => $outputs[$output->floor][$output->line_no]['sewing_rejection']
                    + $output->sewing_rejection,
                'production_minutes' => $outputs[$output->floor][$output->line_no]['production_minutes']
                    + ($output->total_output * $smv),
                'used_minutes' => $this->getAvailableMinutes($output->line->sewingTargetsByDate, $sewing_starting_hour),
                //'used_minutes' => $this->usedMinutes($output->line->sewingTargetsByDate, $minutesWorked),
                'remarks' => $this->remarksOnProduction($output->line->sewingTargetsByDate, $minutesWorked),
            ];

            if ($outputs[$output->floor][$output->line_no]['used_minutes'] > 0) {
                $outputs[$output->floor][$output->line_no]['line_efficiency'] =
                    $outputs[$output->floor][$output->line_no]['production_minutes']
                    / $outputs[$output->floor][$output->line_no]['used_minutes']
                    * 100;
            } else {
                $outputs[$output->floor][$output->line_no]['line_efficiency'] = 0;
            }

            if ($minutesWorked) {
                $outputs[$output->floor][$output->line_no]['hourly_avg_production'] = round(
                    $outputs[$output->floor][$output->line_no]['total_output']
                    / ($minutesWorked / 60)
                );
            } else {
                $outputs[$output->floor][$output->line_no]['hourly_avg_production'] = 0;
            }
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

    private function initialLineValues($floors, $date, $now)
    {
        $outputs = [];

        foreach ($floors as $floor) {
            foreach ($floor->lines->sortBy('sort') as $line) {
                $minutesWorked = $this->minutesWorked($date, $line->sewingTargetsByDate, $now);
                $outputs[$floor->floor_no][$line->line_no] = [
                    'order_count' => 0,
                    'floor_id' => $floor->id,
                    'floor' => $floor->floor_no,
                    'line_id' => $line->id,
                    'line' => $line->line_no,
                    'buyer' => "",
                    'order' => "",
                    'item' => "",
                    'po' => "",
                    'color' => "",
                    'smv' => 0,
                    'hours_worked' => 0,
                    'mp' => $this->sewingManPowner($line->sewingTargetsByDate, $minutesWorked),
                    'wh' => $this->sewingTargetWh($line->sewingTargetsByDate),
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
                    'hourly_avg_production' => 0
                ];
            }
        }

        return $outputs;
    }

    private function floorOutputs($floors, $sewingOutputs)
    {
        $floorOutputs = [];
        $sewing_starting_hour_query = GarmentsProductionEntry::query()->where('factory_id', factoryId())->first();
        $sewing_starting_hour = $sewing_starting_hour_query ? $sewing_starting_hour_query->sewing_starting_hour : 8;
        $current_hour = now()->hour;
        $current_minute = now()->minute;
        $minute_passed = 0;
        if ($current_hour > $sewing_starting_hour && $current_hour < 13) {
            $minute_passed = ($current_hour - $sewing_starting_hour) * 60 + $current_minute;
        } elseif ($current_hour > $sewing_starting_hour && $current_hour == 13) {
            $minute_passed = ($current_hour - $sewing_starting_hour - 1) * 60;
        } elseif ($current_hour > $sewing_starting_hour && $current_hour > 13) {
            $minute_passed = ($current_hour - $sewing_starting_hour - 1) * 60 + $current_minute;
        } elseif ($current_hour == $sewing_starting_hour) {
            $minute_passed = $current_minute;
        }

        foreach ($floors as $floor) {
            $lineOutputCollection = collect($sewingOutputs[$floor->floor_no] ?? []);
            $hour_passed_target = 0;
            foreach ($lineOutputCollection as $lineOutput) {
                $line_minute_passed = $minute_passed < ($lineOutput['wh'] * 60) ? $minute_passed : ($lineOutput['wh'] * 60);
                $line_hour_passed = intdiv($line_minute_passed, 60);
                $line_minute_passed = $line_minute_passed % 60;
                $hour_target = $line_hour_passed * $lineOutput['hourly_target'];
                $minute_target = round(($lineOutput['hourly_target'] * $line_minute_passed) / 60);
                $hour_passed_target += $hour_target + $minute_target;
            }
            $total_hourly_target = $lineOutputCollection->sum('hourly_target');

            $floorOutputs[$floor->floor_no] = [
                'floor_no' => $floor->floor_no,
                'total_hourly_target' => $total_hourly_target,
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
                'hour_passed_target' => $hour_passed_target,
                'total_output' => $lineOutputCollection->sum('total_output'),
                'targeted_output' => $lineOutputCollection->sum('targeted_output'),
                'total_sewing_rejection' => $lineOutputCollection->sum('sewing_rejection'),
                'total_production_minutes' => $lineOutputCollection->sum('production_minutes'),
                'total_used_minutes' => $lineOutputCollection->sum('used_minutes'),
                'floor_efficiency' => 0,
                'total_hourly_avg_production' => $lineOutputCollection->sum('hourly_avg_production'),
            ];

            $floorOutputs[$floor->floor_no]['floor_efficiency'] = 0;
            if ($floorOutputs[$floor->floor_no]['total_used_minutes'] > 0) {
                $floorOutputs[$floor->floor_no]['floor_efficiency'] = $floorOutputs[$floor->floor_no]['total_production_minutes']
                    / $floorOutputs[$floor->floor_no]['total_used_minutes']
                    * 100;
            }

            $floorOutputs[$floor->floor_no]['achievment'] = 0;
            if ($floorOutputs[$floor->floor_no]['targeted_output'] > 0) {
                $floorOutputs[$floor->floor_no]['achievment'] = $floorOutputs[$floor->floor_no]['total_output']
                    / $floorOutputs[$floor->floor_no]['targeted_output']
                    * 100;
            }
        }

        return $floorOutputs;
    }

    private function minutesWorked($date, $targets, $now)
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

    private function getAvailableMinutes($sewingTargetsByDate, $sewing_starting_hour = 8)
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

    private function hourlySewingTarget($targets, $minutesWorked)
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

    private function sewingTargetWh($targets)
    {
        return $targets && $targets->count() ? $targets->sum('wh') : 0;
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
        $remarks = 'Remarks';
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
}
