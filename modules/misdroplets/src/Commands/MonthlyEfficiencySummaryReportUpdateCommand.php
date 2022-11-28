<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class MonthlyEfficiencySummaryReportUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:monthly-efficiency-summary-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Monthly Efficiency Summary Report';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info('Command initiated successfully!!');
            DB::beginTransaction();
            $from_date = $this->ask('Enter Production Date From: (FORMAT: YYYY-MM-DD)');
            $to_date = $this->ask('Enter Production Date To: (FORMAT: YYYY-MM-DD)');
            $checkFromDate = $this->checkValidDate($from_date);
            $checkToDate = $this->checkValidDate($to_date);
            if (!$checkFromDate['status'] || !$checkToDate['status']) {
                $this->info('Invalid Date Given!!');
                return false;
            }
            $fromDate = $checkFromDate['date']->toDateString();
            $toDate = $checkToDate['date']->toDateString();
            $period = Carbon::parse($fromDate)->range($toDate);

            $floors = Floor::query()
                ->withoutGlobalScope('factoryId')
                ->with('linesWithoutGlobalScope:id,line_no,floor_id')
                ->orderBy('sort', 'asc')
                ->get();

            foreach ($period as $date) {
                $dateFormat = $date->format('Y-m-d');
                $sewingOutputs = $this->sewingOutputsByDate($floors, $dateFormat);
                if ($sewingOutputs && count($sewingOutputs)) {
                    $this->removeData($dateFormat);
                    $this->updateData($dateFormat, $sewingOutputs);
                }
            }

            DB::commit();
            $this->info('Command executed successfully!!');
        } catch (Exception $e) {
            DB::rollBack();
            $this->info('Something went wrong!!');
            $this->info($e->getMessage());
        }
    }

    private function updateData($date, $data)
    {
        foreach ($data as $floor_no => $floorData) {
            foreach ($floorData as $line_no => $line_data) {
                DB::table('monthly_efficiency_summary_reports')
                    ->insert([
                        'report_date' => $date,
                        'floor_id' => $line_data['floor_id'],
                        'line_id' => $line_data['line_id'],
                        'used_minutes' => round($line_data['used_minutes'], 2),
                        'produced_minutes' => round($line_data['production_minutes'], 2),
                        'line_efficiency' => round($line_data['line_efficiency'], 2),
                        'factory_id' => $line_data['factory_id'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            }
        }
    }

    private function removeData($date)
    {
        return DB::table('monthly_efficiency_summary_reports')->whereDate('report_date', $date)->delete();
    }

    private function checkValidDate($date)
    {
        try {
            return [
                'date' => Carbon::parse($date),
                'status' => true
            ];
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            return [
                'date' => null,
                'status' => false
            ];
        }
    }

    private function sewingOutputsByDate($floors, $date)
    {
        Line::$targetDate = $date;
        $now = Carbon::parse($date)->endOfDay();

        $sewingOutputs = HourlySewingProductionReport::query()
            ->withoutGlobalScope('factoryId')
            ->with([
                'floorWithoutGLobalScope:id,floor_no',
                'lineWithoutGlobalScopes.sewingTargetsByDateWithoutGlobalScopes',
                'buyerWithoutGlobalScope:id,name',
                'orderWithoutGlobalScope:id,style_name,smv,item_details',
                'purchaseOrderWithoutGlobalScope:id,po_no',
                'colorWithoutGLobalScope:id,name',
            ])
            ->where('production_date', $date)
            ->orderBy('updated_at', 'asc')
            ->get();
        $outputs = $this->initialLineValues($floors, $date, $now);

        foreach ($sewingOutputs as $output) {
            $order_id = $output->order_id;
            $garments_item_id = $output->garments_item_id;
            $smv = Order::getOrderItemWiseFactorySMV($order_id, $garments_item_id, true) ?? Order::getOrderItemWiseSMV($order_id, $garments_item_id) ?? $output->order->smv ?? 0;
            $minutesWorked = $this->minutesWorked($date, $output->lineWithoutGlobalScopes->sewingTargetsByDateWithoutGlobalScopes, $now);

            $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no] = [
                'order_count' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['order_count'] + 1,
                'floor_id' => $output->floor_id ?? null,
                'floor' => $output->floorWithoutGLobalScope->floor_no ?? 'N/A',
                'line_id' => $output->line_id ?? null,
                'line' => $output->lineWithoutGlobalScopes->line_no ?? 'N/A',
                'smv' => $smv ?? 0,
                'hours_worked' => ($minutesWorked / 60),
                'mp' => $this->sewingManPowner($output->lineWithoutGlobalScopes->sewingTargetsByDateWithoutGlobalScopes, $minutesWorked),
                'hourly_target' => $this->hourlySewingTarget($output->lineWithoutGlobalScopes->sewingTargetsByDateWithoutGlobalScopes, $minutesWorked),
                'day_target' => $this->sewingTargetForDay($output->lineWithoutGlobalScopes->sewingTargetsByDateWithoutGlobalScopes),
                'hour_0' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_0'] + $output->hour_0,
                'hour_1' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_1'] + $output->hour_1,
                'hour_2' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_2'] + $output->hour_2,
                'hour_3' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_3'] + $output->hour_3,
                'hour_4' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_4'] + $output->hour_4,
                'hour_5' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_5'] + $output->hour_5,
                'hour_6' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_6'] + $output->hour_6,
                'hour_7' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_7'] + $output->hour_7,
                'hour_8' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_8'] + $output->hour_8,
                'hour_9' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_9'] + $output->hour_9,
                'hour_10' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_10'] + $output->hour_10,
                'hour_11' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_11'] + $output->hour_11,
                'hour_12' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_12'] + $output->hour_12,
                'hour_13' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_13'] + $output->hour_13,
                'hour_14' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_14'] + $output->hour_14,
                'hour_15' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_15'] + $output->hour_15,
                'hour_16' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_16'] + $output->hour_16,
                'hour_17' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_17'] + $output->hour_17,
                'hour_18' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_18'] + $output->hour_18,
                'hour_19' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_19'] + $output->hour_19,
                'hour_20' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_20'] + $output->hour_20,
                'hour_21' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_21'] + $output->hour_21,
                'hour_22' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_22'] + $output->hour_22,
                'hour_23' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hour_23'] + $output->hour_23,
                'total_output' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['total_output']
                    + $output->total_output,
                'targeted_output' => $this->targetedOutput($output->lineWithoutGlobalScopes->sewingTargetsByDateWithoutGlobalScopes, $minutesWorked),
                'sewing_rejection' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['sewing_rejection']
                    + $output->sewing_rejection,
                'production_minutes' => $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['production_minutes']
                    + ($output->total_output * $smv),
                'used_minutes' => $this->getAvailableMinutes($output->lineWithoutGlobalScopes->sewingTargetsByDateWithoutGlobalScopes, $now),
                'factory_id' => $output->factory_id
            ];

            if ($outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['used_minutes'] > 0) {
                $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['line_efficiency'] =
                    $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['production_minutes']
                    / $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['used_minutes']
                    * 100;
            } else {
                $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['line_efficiency'] = 0;
            }

            if ($minutesWorked) {
                $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hourly_avg_production'] = round(
                    $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['total_output']
                        / ($minutesWorked / 60)
                );
            } else {
                $outputs[$output->floorWithoutGLobalScope->floor_no][$output->lineWithoutGlobalScopes->line_no]['hourly_avg_production'] = 0;
            }
        }

        return $outputs;
    }

    private function initialLineValues($floors, $date, $now)
    {
        $outputs = [];

        foreach ($floors as $floor) {
            foreach ($floor->linesWithoutGlobalScope->sortBy('sort') as $line) {
                $minutesWorked = $this->minutesWorked($date, $line->sewingTargetsByDateWithoutGlobalScopes, $now);
                $outputs[$floor->floor_no][$line->line_no] = [
                    'order_count' => 0,
                    'floor_id' => $floor->id,
                    'floor' => $floor->floor_no,
                    'line_id' => $line->id,
                    'line' => $line->line_no,
                    'smv' => 0,
                    'hours_worked' => 0,
                    'mp' => $this->sewingManPowner($line->sewingTargetsByDateWithoutGlobalScopes, $minutesWorked),
                    'total_plan_target' => 0,
                    'hourly_target' => $this->hourlySewingTarget($line->sewingTargetsByDateWithoutGlobalScopes, $minutesWorked),
                    'day_target' => $this->sewingTargetForDay($line->sewingTargetsByDateWithoutGlobalScopes),
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
                    'line_efficiency' => 0,
                    'hourly_avg_production' => 0,
                    'factory_id' => $floor->factory_id,
                ];
            }
        }
        return $outputs;
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

    private function sewingTargetForDay($targets)
    {
        $dayTarget = 0;
        foreach ($targets as $key => $target) {
            $dayTarget += $target->target * $target->wh;
        }

        return $dayTarget;
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

    private function getAvailableMinutes($sewingTargetsByDate, $now)
    {
        $available_minutes = 0;
        if ($sewingTargetsByDate->count()) {
            $start_wh = 8;
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
                    if ($current_date < $target_date || ($current_date == $target_date && $i > $current_hour)) {
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
}
