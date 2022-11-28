<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;

class UpdateDateWiseHourlySewingProductionSummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:date-wise-hourly-sewing-production-summary-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update DateWiseHourlySewingProductionSummary Model';

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
     * Validate an input.
     *
     * @param  mixed   $method
     * @param  array   $rules
     * @return string
     */
    public function validate_cmd($method, $rules)
    {
        $value = $method();
        $validate = $this->validateInput($rules, $value);

        if ($validate !== true) {
            $this->warn($validate);
            $value = $this->validate_cmd($method, $rules);
        }
        return $value;
    }

    public function validateInput($rules, $value)
    {
        $validator = Validator::make([$rules[0] => $value], [$rules[0] => $rules[1]]);

        if ($validator->fails()) {
            $error = $validator->errors();
            return $error->first($rules[0]);
        } else {
            return true;
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info("Execution Started!");
            $date = $this->validate_cmd(function () {
                return $this->ask('Enter date [Eg: 2016-01-01] to update production data for this date');
            }, ['date', 'required|date']);
            DB::beginTransaction();
            $this->processFactoryWiseData($date);
            DB::commit();
            $this->info("Execution Ended Successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            $this->info("Something went wrong!");
            $this->info($e->getMessage());
        }
    }

    private function processFactoryWiseData($date)
    {
        $factories = DB::table('factories')->whereNull('deleted_at')->pluck('id')->toArray();
        if ($factories && count($factories)) {
            foreach ($factories as $factoryId) {
                $summary_data = $this->getproductionDashboardDataByFloor($date, $factoryId);

                $reportQuery = DB::table('date_wise_hourly_sewing_production_summaries')
                    ->where('production_date', $date);
                
                if (!$reportQuery->first()) {
                    DB::table('date_wise_hourly_sewing_production_summaries')
                        ->insert([
                            'production_date' => $date,
                            'summary_data' => json_encode($summary_data, true),
                            'factory_id' => $factoryId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                } else {
                    $reportQuery->update([
                        'production_date' => $date,
                        'summary_data' => json_encode($summary_data, true),
                        'factory_id' => $factoryId,
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function getproductionDashboardDataByFloor($date, $factoryId)
    {
        $date = Carbon::parse($date)->toDateString();
        Line::$targetDate = $date;
        $floors = Floor::query()
            ->with(['linesWithoutGlobalScope:id,line_no,floor_id,sort', 'linesWithoutGlobalScope.sewingTargetsByDateWithoutGlobalScopes'])
            ->withoutGlobalScope('factoryId')
            ->where('factory_id', $factoryId)
            ->orderBy('sort')
            ->get()
            ->map(function ($floor) {
                $lines = [];
                foreach ($floor->linesWithoutGlobalScope as $line) {
                    $lines[] = [
                        'id' => $line->id,
                        'floor_id' => $line->floor_id,
                        'line_no' => $line->line_no,
                        'sort' => $line->sort,
                        'sewingTargetsByDate' => $line->sewingTargetsByDateWithoutGlobalScopes->toArray(),
                    ];
                }
                return [
                    'id' => $floor->id,
                    'floor_no' => $floor->floor_no,
                    'sort' => $floor->sort,
                    'lines' => $lines,
                ];
            });

        $sewingOutputs = $this->sewingOutputsByDate($floors, $date, $factoryId);

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

        return [
            'data' => $data,
            'floors' => $floors
        ];
    }

    private function getSwingStartingHour($factoryId)
    {
        $sewing_starting_hour_query = GarmentsProductionEntry::query()->where('factory_id', $factoryId)->first();
        return $sewing_starting_hour_query ? $sewing_starting_hour_query->sewing_starting_hour : 8;
    }

    private function sewingOutputsByDate($floors, $date, $factoryId)
    {
        $now = Carbon::parse($date)->endOfDay();

        $sewingOutputs = HourlySewingProductionReport::query()
            ->withoutGlobalScope('factoryId')
            ->with([
                'factory:id,factory_name',
                'buyerWithoutGlobalScope:id,name',
                'colorWithoutGLobalScope:id,name',
                'garmentsItem:id,name',
                'orderWithoutGlobalScope:id,style_name,reference_no',
                'purchaseOrderWithoutGlobalScope:id,po_no',
                'lineWithoutGlobalScopes:id,line_no,floor_id,sort',
                'lineWithoutGlobalScopes.sewingTargetsByDateWithoutGlobalScopes',
                'floorWithoutGLobalScope:id,floor_no,sort'
                ])
            ->where('factory_id', $factoryId)
            ->where('production_date', $date)
            ->orderBy('updated_at', 'asc')
            ->get()
            ->map(function ($sewingOutput) {
                $floor = $sewingOutput->floorWithoutGLobalScope->toArray();

                $line = [
                    'id' => $sewingOutput->lineWithoutGlobalScopes->id,
                    'floor_id' => $sewingOutput->lineWithoutGlobalScopes->floor_id,
                    'line_no' => $sewingOutput->lineWithoutGlobalScopes->line_no,
                    'sort' => $sewingOutput->lineWithoutGlobalScopes->sort,
                    'sewingTargetsByDate' => $sewingOutput->lineWithoutGlobalScopes->sewingTargetsByDateWithoutGlobalScopes->toArray(),
                ];

                $buyer = [
                    'id' => $sewingOutput->buyerWithoutGlobalScope->id,
                    'name' => $sewingOutput->buyerWithoutGlobalScope->name,
                ];

                $color = [
                    'id' => $sewingOutput->colorWithoutGLobalScope->id,
                    'name' => $sewingOutput->colorWithoutGLobalScope->name,
                ];

                $order = [
                    'id' => $sewingOutput->orderWithoutGlobalScope->id,
                    'style_name' => $sewingOutput->orderWithoutGlobalScope->style_name,
                    'reference_no' => $sewingOutput->orderWithoutGlobalScope->reference_no,
                ];

                $purchaseOrder = [
                    'id' => $sewingOutput->purchaseOrderWithoutGlobalScope->id,
                    'po_no' => $sewingOutput->purchaseOrderWithoutGlobalScope->po_no,
                ];

                $garmentsItem = [
                    'id' => $sewingOutput->garmentsItem->id,
                    'name' => $sewingOutput->garmentsItem->name,
                ];

                return [
                    'id' => $sewingOutput->id,
                    'production_date' => $sewingOutput->production_date,
                    'floor_id' => $sewingOutput->floor_id,
                    'floor' => $floor,
                    'line_id' => $sewingOutput->line_id,
                    'line' => $line,
                    'buyer_id' => $sewingOutput->buyer_id,
                    'buyer' => $buyer,
                    'garments_item_id' => $sewingOutput->garments_item_id,
                    'garmentsItem' => $garmentsItem,
                    'order_id' => $sewingOutput->order_id,
                    'order' => $order,
                    'purchase_order_id' => $sewingOutput->purchase_order_id,
                    'purchaseOrder' => $purchaseOrder,
                    'color_id' => $sewingOutput->color_id,
                    'color' => $color,
                    'hour_0' => $sewingOutput->hour_0,
                    'hour_1' => $sewingOutput->hour_1,
                    'hour_2' => $sewingOutput->hour_2,
                    'hour_3' => $sewingOutput->hour_3,
                    'hour_4' => $sewingOutput->hour_4,
                    'hour_5' => $sewingOutput->hour_5,
                    'hour_6' => $sewingOutput->hour_6,
                    'hour_7' => $sewingOutput->hour_7,
                    'hour_8' => $sewingOutput->hour_8,
                    'hour_9' => $sewingOutput->hour_9,
                    'hour_10' => $sewingOutput->hour_10,
                    'hour_11' => $sewingOutput->hour_11,
                    'hour_12' => $sewingOutput->hour_12,
                    'hour_13' => $sewingOutput->hour_13,
                    'hour_14' => $sewingOutput->hour_14,
                    'hour_15' => $sewingOutput->hour_15,
                    'hour_16' => $sewingOutput->hour_16,
                    'hour_17' => $sewingOutput->hour_17,
                    'hour_18' => $sewingOutput->hour_18,
                    'hour_19' => $sewingOutput->hour_19,
                    'hour_20' => $sewingOutput->hour_20,
                    'hour_21' => $sewingOutput->hour_21,
                    'hour_22' => $sewingOutput->hour_22,
                    'hour_23' => $sewingOutput->hour_23,
                    'sewing_rejection' => $sewingOutput->sewing_rejection,
                    'factory_id' => $sewingOutput->factory_id,
                    'total_output' => $sewingOutput->total_output,
                    'factory' => $sewingOutput->factory->toArray(),
                ];
            });
        $sewing_starting_hour = $this->getSwingStartingHour($factoryId);

        $outputs = $this->initialLineValues($floors, $date, $now);

        foreach ($sewingOutputs as $output) {
            $order_id = $output['order_id'];
            $garments_item_id = $output['garments_item_id'];
            $smv = Order::getOrderItemWiseFactorySMV($order_id, $garments_item_id, true) ?? Order::getOrderItemWiseSMV($order_id, $garments_item_id, true) ?? 0;
            $minutesWorked = $this->minutesWorked($date, $output['line']['sewingTargetsByDate'], $now);

            $outputs[$output['floor']['floor_no']][$output['line']['line_no']] = [
                'order_count' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['order_count'] + 1,
                'floor_id' => $output['floor_id'] ?? null,
                'floor' => $output['floor']['floor_no'] ?? 'N/A',
                'line_id' => $output['line_id'] ?? null,
                'line' => $output['line']['line_no'] ?? 'N/A',
                'buyer_id' => $output['buyer']['id'] ?? null,
                'buyer' => $output['buyer']['name'] ?? 'N/A',
                'order_id' => $output['order']['id'] ?? null,
                'order' => $output['order']['style_name'] ?? 'N/A',
                'garments_item_id' => $output['garmentsItem']['id'] ?? null,
                'item' => $output['garmentsItem']['name'] ?? 'N/A',
                'purchase_order_id' => $output['purchaseOrder']['id'] ?? null,
                'po' => $output['purchaseOrder']['po_no'] ?? 'N/A',
                'color_id' => $output['color']['id'] ?? null,
                'color' => $output['color']['name'] ?? 'N/A',
                'smv' => $smv ?? 0,
                'hours_worked' => ($minutesWorked / 60),
                'mp' => $this->sewingManPowner($output['line']['sewingTargetsByDate'], $minutesWorked),
                'wh' => $this->sewingTargetWh($output['line']['sewingTargetsByDate']),
                'hourly_target' => $this->hourlySewingTarget($output['line']['sewingTargetsByDate'], $minutesWorked),
                'day_target' => $this->sewingTargetForDay($output['line']['sewingTargetsByDate']),
                'hour_0' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_0'] + $output['hour_0'],
                'hour_1' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_1'] + $output['hour_1'],
                'hour_2' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_2'] + $output['hour_2'],
                'hour_3' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_3'] + $output['hour_3'],
                'hour_4' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_4'] + $output['hour_4'],
                'hour_5' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_5'] + $output['hour_5'],
                'hour_6' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_6'] + $output['hour_6'],
                'hour_7' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_7'] + $output['hour_7'],
                'hour_8' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_8'] + $output['hour_8'],
                'hour_9' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_9'] + $output['hour_9'],
                'hour_10' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_10'] + $output['hour_10'],
                'hour_11' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_11'] + $output['hour_11'],
                'hour_12' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_12'] + $output['hour_12'],
                'hour_13' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_13'] + $output['hour_13'],
                'hour_14' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_14'] + $output['hour_14'],
                'hour_15' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_15'] + $output['hour_15'],
                'hour_16' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_16'] + $output['hour_16'],
                'hour_17' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_17'] + $output['hour_17'],
                'hour_18' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_18'] + $output['hour_18'],
                'hour_19' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_19'] + $output['hour_19'],
                'hour_20' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_20'] + $output['hour_20'],
                'hour_21' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_21'] + $output['hour_21'],
                'hour_22' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_22'] + $output['hour_22'],
                'hour_23' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hour_23'] + $output['hour_23'],
                'total_output' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['total_output']
                    + $output['total_output'],
                'targeted_output' => $this->targetedOutput($output['line']['sewingTargetsByDate'], $minutesWorked),
                'sewing_rejection' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['sewing_rejection']
                    + $output['sewing_rejection'],
                'production_minutes' => $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['production_minutes']
                    + ($output['total_output'] * $smv),
                'used_minutes' => $this->getAvailableMinutes($now, $output['line']['sewingTargetsByDate'], $sewing_starting_hour),
                'remarks' => $this->remarksOnProduction($output['line']['sewingTargetsByDate'], $minutesWorked),
            ];

            if ($outputs[$output['floor']['floor_no']][$output['line']['line_no']]['used_minutes'] > 0) {
                $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['line_efficiency'] =
                    $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['production_minutes']
                    / $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['used_minutes']
                    * 100;
            } else {
                $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['line_efficiency'] = 0;
            }

            if ($minutesWorked) {
                $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hourly_avg_production'] = round(
                    $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['total_output']
                        / ($minutesWorked / 60)
                );
            } else {
                $outputs[$output['floor']['floor_no']][$output['line']['line_no']]['hourly_avg_production'] = 0;
            }
        }

        // add total row each floor
        $floor_total = $this->floorOutputs($floors, $outputs, $factoryId, $now);
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
            foreach (collect($floor['lines'])->sortBy('sort') as $line) {
                $minutesWorked = $this->minutesWorked($date, $line['sewingTargetsByDate'], $now);
                $outputs[$floor['floor_no']][$line['line_no']] = [
                    'order_count' => 0,
                    'floor_id' => $floor['id'],
                    'floor' => $floor['floor_no'],
                    'line_id' => $line['id'],
                    'line' => $line['line_no'],
                    'buyer_id' => "",
                    'buyer' => "",
                    'order_id' => "",
                    'order' => "",
                    'garments_item_id' => "",
                    'item' => "",
                    'purchase_order_id' => "",
                    'po' => "",
                    'color_id' => "",
                    'color' => "",
                    'smv' => 0,
                    'hours_worked' => 0,
                    'mp' => $this->sewingManPowner($line['sewingTargetsByDate'], $minutesWorked),
                    'wh' => $this->sewingTargetWh($line['sewingTargetsByDate']),
                    'total_plan_target' => 0,
                    'hourly_target' => $this->hourlySewingTarget($line['sewingTargetsByDate'], $minutesWorked),
                    'day_target' => $this->sewingTargetForDay($line['sewingTargetsByDate']),
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
                    'remarks' => $this->remarksOnProduction($line['sewingTargetsByDate'], $minutesWorked),
                    'line_efficiency' => 0,
                    'hourly_avg_production' => 0
                ];
            }
        }

        return $outputs;
    }

    private function minutesWorked($date, $targets, $now)
    {
        $minutesWorked = collect($targets)->sum('wh') * 60;

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

            $manPower = $target['operator'] + $target['helper'];

            $hours -= $target['wh'];
        }

        return $manPower;
    }

    private function sewingTargetWh($targets)
    {
        return $targets && count($targets) ? collect($targets)->sum('wh') : 0;
    }

    private function hourlySewingTarget($targets, $minutesWorked)
    {
        $hourlyTarget = 0;
        $hours = $minutesWorked / 60;

        foreach ($targets as $key => $target) {
            if ($hours <= 0) {
                break;
            }

            $hourlyTarget = $target['target'];

            $hours -= $target['wh'];
        }

        return $hourlyTarget;
    }

    private function sewingTargetForDay($targets)
    {
        $dayTarget = 0;
        foreach ($targets as $key => $target) {
            $dayTarget += $target['target'] * $target['wh'];
        }

        return $dayTarget;
    }

    private function remarksOnProduction($targets, $minutesWorked)
    {
        $remarks = '';
        $hours = $minutesWorked / 60;

        foreach ($targets as $key => $target) {
            if ($hours <= 0) {
                break;
            }

            $remarks = $target['remarks'];

            $hours -= $target['wh'];
        }

        return $remarks;
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

            if ($hours > $sewingLineTarget['wh']) {
                $targetedOutput += $sewingLineTarget['wh'] * $sewingLineTarget['target'];
            } else {
                $targetedOutput += $hours * $sewingLineTarget['target'];
            }

            $hours -= $sewingLineTarget['wh'];
        }

        return $targetedOutput;
    }

    private function getAvailableMinutes($now, $sewingTargetsByDate, $sewing_starting_hour = 8)
    {
        $available_minutes = 0;
        if ($sewingTargetsByDate && count($sewingTargetsByDate)) {
            $start_wh = $sewing_starting_hour;
            $end_wh = 0;
            $total_hourly_target = 0;
            $available_minutes = 0;
            $current_date = date('Y-m-d');
            $current_hour = (int)date('H');
            $current_minute = $now->minute;
            foreach ($sewingTargetsByDate as $sewing_target) {
                $target_date = date('Y-m-d', strtotime($sewing_target['target_date']));
                $end_wh += $start_wh + $sewing_target['wh'];
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
                    $total_hourly_target += $sewing_target['target'];
                }
                $start_wh += $end_wh;
                if ($current_date < $target_date) {
                    continue;
                }
                $completed_work_hour = $completed_work_minutes / 60;
                if ($completed_work_hour >= $sewing_target['wh']) {
                    $completed_work_hour = $sewing_target['wh'];
                }
                $available_minutes += ($sewing_target['operator'] + $sewing_target['helper']) * $completed_work_hour * 60;
            }
        }
        return $available_minutes;
    }

    private function floorOutputs($floors, $sewingOutputs, $factoryId, $now)
    {
        $floorOutputs = [];
        $sewing_starting_hour = $this->getSwingStartingHour($factoryId);
        $current_hour = $now->hour;
        $hour_passed = $current_hour > $sewing_starting_hour ? $current_hour - $sewing_starting_hour + 1 : 0;

        foreach ($floors as $floor) {
            $lineOutputCollection = collect($sewingOutputs[$floor['floor_no']] ?? []);
            $hour_passed_target = 0;
            foreach ($lineOutputCollection as $lineOutput) {
                $line_hour_passed = $hour_passed < $lineOutput['wh'] ? $hour_passed : $lineOutput['wh'];
                $hour_passed_target += $line_hour_passed * $lineOutput['hourly_target'];
            }
            $total_hourly_target = $lineOutputCollection->sum('hourly_target');

            $floorOutputs[$floor['floor_no']] = [
                'floor_no' => $floor['floor_no'],
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

            $floorOutputs[$floor['floor_no']]['floor_efficiency'] = 0;
            if ($floorOutputs[$floor['floor_no']]['total_used_minutes'] > 0) {
                $floorOutputs[$floor['floor_no']]['floor_efficiency'] = $floorOutputs[$floor['floor_no']]['total_production_minutes']
                    / $floorOutputs[$floor['floor_no']]['total_used_minutes']
                    * 100;
            }

            $floorOutputs[$floor['floor_no']]['achievment'] = 0;
            if ($floorOutputs[$floor['floor_no']]['targeted_output'] > 0) {
                $floorOutputs[$floor['floor_no']]['achievment'] = $floorOutputs[$floor['floor_no']]['total_output']
                    / $floorOutputs[$floor['floor_no']]['targeted_output']
                    * 100;
            }
        }

        return $floorOutputs;
    }
}
