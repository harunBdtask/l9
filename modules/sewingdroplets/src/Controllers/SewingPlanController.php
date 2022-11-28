<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingHoliday;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingLineCapacity;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\Sewingoutput;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingPlan;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingPlanDetail;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingWorkingHour;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\UndoRedoSewingPlan;
use Session, Exception;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;

class SewingPlanController extends Controller
{
    public function sewingPlan()
    {
        $undo_redo_first_data = DB::table('undo_redo_sewing_plans')->first();
        $id = $undo_redo_first_data->id ?? '';
        $factory_name = sessionFactoryName();
        if ($id) {
            DB::table('undo_redo_sewing_plans')
                ->where('id', $id)
                ->update([
                    'undo_redo_status' => 0
                ]);
        }
        return view('sewingdroplets::pages.sewing_planning_board', ['factory_name' => $factory_name]);
    }

    public function getLinesForSewingPlan()
    {
        $lines_query = SewingLineCapacity::withoutGlobalScope('factoryId')
            ->leftJoin('lines', 'lines.id', 'sewing_line_capacities.line_id')
            ->leftJoin('floors', 'floors.id', 'sewing_line_capacities.floor_id')
            ->where('sewing_line_capacities.factory_id', factoryId())
            ->orderBy('sewing_line_capacities.floor_id')
            ->select('lines.*', 'floors.floor_no')
            ->get();

        $sewing_holiday_query = SewingHoliday::get();
        $sewing_holidays = [];
        foreach ($sewing_holiday_query as $sewing_holiday) {
            $sewing_holidays[] = [
                'start_date' => Carbon::parse($sewing_holiday->holiday)->startOfDay()->toDateTimeString(),
                'end_date' => Carbon::parse($sewing_holiday->holiday)->endOfDay()->toDateTimeString()
            ];
        }
        $lines = [];
        foreach ($lines_query->sortBy('sort') as $line) {
            $lines[] = ['key' => $line->id, 'label' => $line->line_no, 'line' => $line];
        }

        return [
            'lines' => $lines,
            'sewing_holidays' => $sewing_holidays
        ];
    }

    public function getCreatePlanForm(Request $request)
    {
        try {
            if (!$request->has('purchase_order_id') || !$request->has('garments_item_id')) {
                $html = view('partials.flash_message', [
                    'message_class' => "danger",
                    'message' => "Please select at least one PO!"
                ])->render();

                return response()->json([
                    'status' => 'danger',
                    'errors' => null,
                    'message' => $html
                ]);
            }
            $garments_item_id = $request->garments_item_id;
            $purchase_order_ids = $request->purchase_order_id;
            $smv = $request->smv;

            $po_item_details = PoColorSizeBreakdown::query()
                ->where('garments_item_id', $garments_item_id)
                ->whereIn('purchase_order_id', $purchase_order_ids)
                ->get();

            $sewing_planned_qty_array = SewingPlanDetail::query()
                ->where('garments_item_id', $garments_item_id)
                ->whereIn('purchase_order_id', $purchase_order_ids)
                ->selectRaw('SUM(allocated_qty) as allocated_qty, garments_item_id, purchase_order_id')
                ->groupBy('purchase_order_id')
                ->get();

            $floors = Floor::pluck('floor_no', 'id');

            $html = view('sewingdroplets::forms.sewing_plan_create', [
                'po_item_details' => $po_item_details,
                'smv' => $smv,
                'sewing_planned_qty_array' => $sewing_planned_qty_array,
                'floors' => $floors,
            ])->render();

            return response()->json([
                'status' => 'success',
                'html' => $html
            ]);
        } catch (Exception $e) {
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!"
            ])->render();

            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
                'message' => $html
            ]);
        }
    }

    public function getEndDateTimeForPlan($start_date, $smv, $line_id, $allocated_qty)
    {
        try {
            $check_start_date_is_holiday = $this->checkDateIsHoliday(Carbon::parse($start_date));

            if ($check_start_date_is_holiday) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'start_date' => [0 => 'Start date cannot be a holiday!!']
                    ],
                ]);
            }

            $check_if_already_plan_exist_in_this_date = $this->checkPlanExistInThisDate(Carbon::parse($start_date), $line_id);
            if ($check_if_already_plan_exist_in_this_date['status']) {
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'start_date' => [0 => 'Plan already exists in this day!!']
                    ],
                ]);
            };

            $start_time = $check_if_already_plan_exist_in_this_date['plan_start_time'] ? date('H:i:s', strtotime($check_if_already_plan_exist_in_this_date['plan_start_time'])) : date('H:i:s', strtotime('08:00:00'));
            $start_date_time = Carbon::parse($start_date . ' ' . $start_time);

            $sewing_line_capacity = SewingLineCapacity::where('line_id', $line_id)->first();

            $capacity_required_minutes = $allocated_qty * $smv;
            $capacity_available_minutes = $sewing_line_capacity->capacity_available_minutes ?? 0;
            $line_working_hour = $sewing_line_capacity->working_hour ?? 0;
            if ($capacity_available_minutes == 0) {
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Capacity Mins is 0. Cannot calculate end date time.',
                    'start_date' => $start_date,
                ]);
            } elseif ($line_working_hour == 0) {
                return response()->json([
                    'status' => 'danger',
                    'message' => 'Working hour is 0. Cannot calculate end date time.',
                    'start_date' => $start_date,
                ]);
            }
            $required_days = $capacity_available_minutes > 0 ? $capacity_required_minutes / $capacity_available_minutes : 0;
            $required_working_seconds = $required_days * $line_working_hour * 3600;
            $temp_required_working_seconds = $required_working_seconds;
            $temp_start_date = clone $start_date_time;
            $temp_start_time = $start_time;

            while ($required_working_seconds > 0) {
                $day_start_time = $temp_start_time;
                $this_date = clone $temp_start_date;
                $check_start_date_is_holiday = $this->checkDateIsHoliday($temp_start_date);
                if ($check_start_date_is_holiday) {
                    $temp_start_date->addDay(1);
                    continue;
                }
                $check_this_date_working_hour = $this->checkDateWorkingHour($temp_start_date);
                $this_date_available_working_seconds = $check_this_date_working_hour * 3600;
                $date_work_start_time = Carbon::parse($this_date->toDateString() . ' ' . date('H:i:s', strtotime("08:00:00")));
                $date_work_end_time = Carbon::parse($this_date->toDateString() . ' ' . date('H:i:s', strtotime("08:00:00")))->addSeconds($this_date_available_working_seconds);
                $plan_start_time = Carbon::parse($this_date->toDateString() . ' ' . $day_start_time);

                if ($plan_start_time > $date_work_start_time) {
                    $this_date_available_working_seconds = $plan_start_time->diffInSeconds($date_work_end_time);
                }
                $temp_start_date = Carbon::parse($temp_start_date->toDateString() . ' ' . $plan_start_time->toTimeString());

                if ($this_date_available_working_seconds <= $required_working_seconds) {
                    $end_date_time = $temp_start_date->addDay(1);
                    $temp_start_time = date('H:i:s', strtotime("08:00:00"));
                } else {
                    $end_date_time = $temp_start_date->addSeconds($required_working_seconds);
                }
                $required_working_seconds -= $this_date_available_working_seconds;
            }

            $last_date_working_hour = $check_this_date_working_hour;
            $required_full_days = (int)$required_days;
            $last_day = $required_days - $required_full_days;
            $last_day_working_hours = ($last_day - (int)$last_day) * $last_date_working_hour;
            $last_day_working_mins = ($last_day_working_hours - (int)$last_day_working_hours) * 60;
            $last_day_working_seconds = ($last_day_working_mins - (int)$last_day_working_mins) * 60;
            $end_date = clone $end_date_time;
            $end_date = $end_date->toDateString();
            $end_time = clone $end_date_time;
            $end_time = $end_time->toTimeString();

            $sample_data = [
                $start_date_time,
                $required_days,
                $temp_required_working_seconds,
                $last_day,
                $last_date_working_hour,
                $required_full_days . ' D',
                (int)$last_day_working_hours . ' H',
                (int)$last_day_working_mins . ' m',
                $last_day_working_seconds . ' s',
                $end_date_time
            ];

            $data = [
                'status' => 'success',
                'start_date' => $start_date,
                'start_time' => $start_time,
                'end_date' => $end_date,
                'end_time' => $end_time,
                'total_required_seconds' => $temp_required_working_seconds,
                'sample_data' => $sample_data,
            ];

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'danger',
                'message' => 'Something went wrong',
                'start_date' => $start_date,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function checkPlanExistInThisDate(Carbon $start_date, $line_id, $factory_id = '', $plan_id = '')
    {
        if ($factory_id && $plan_id) {
            $check_if_holiday = $this->checkDateIsHoliday($start_date, $factory_id);
            if ($check_if_holiday) {
                return [
                    'status' => true,
                    'plan_start_time' => null
                ];
            }
            $sewing_plan_query = SewingPlan::withoutGlobalScope('factoryId')
                ->where('id', '!=', $plan_id)
                ->where(['line_id' => $line_id, 'factory_id' => $factory_id])
                ->whereDate('start_date', '<=', $start_date->toDateString())
                ->whereDate('end_date', '>=', $start_date->toDateString())
                ->orderBy('end_date', 'desc');
        } else {
            $check_if_holiday = $this->checkDateIsHoliday($start_date);
            if ($check_if_holiday) {
                return [
                    'status' => true,
                    'plan_start_time' => null
                ];
            }
            $sewing_plan_query = SewingPlan::where('line_id', $line_id)
                ->whereDate('start_date', '<=', $start_date->toDateString())
                ->whereDate('end_date', '>=', $start_date->toDateString())
                ->orderBy('end_date', 'desc');
        }

        if ($sewing_plan_query->count() == 0) {
            return [
                'status' => false,
                'plan_start_time' => '08:00:00'
            ];
        }
        $last_sewing_plan = $sewing_plan_query->first();
        $plan_end_date = Carbon::parse($last_sewing_plan->end_date)->toDateString();
        $plan_end_date_time = Carbon::parse($last_sewing_plan->end_date)->toDateTimeString();
        if ($start_date->toDateString() < $plan_end_date) {
            return [
                'status' => true,
                'plan_start_time' => null
            ];
        }
        if ($factory_id) {
            $working_hour_query = SewingWorkingHour::withoutGlobalScope('factoryId')
                ->where(['working_date' => $plan_end_date, 'factory_id' => $factory_id]);
        } else {
            $working_hour_query = SewingWorkingHour::where('working_date', $plan_end_date);
        }
        $working_hour = 10;
        if ($working_hour_query->count()) {
            $working_hour = $working_hour_query->first()->working_hour;
        }
        $working_seconds = $working_hour * 3600;
        $work_start_time = date('H:i:s', strtotime('08:00:00'));
        $working_end_time = Carbon::parse($plan_end_date . ' ' . $work_start_time)->addSeconds($working_seconds);
        $temp_working_end_time = clone $working_end_time;

        if ($plan_end_date_time >= $temp_working_end_time->toDateTimeString()) {
            return [
                'status' => true,
                'plan_start_time' => null
            ];
        } else {
            return [
                'status' => false,
                'plan_start_time' => Carbon::parse($last_sewing_plan->end_date)->toTimeString()
            ];
        }
    }

    private function checkDateIsHoliday(Carbon $date, $factory_id = '')
    {
        // Friday default holiday checking is omitted
        $holiday = false;
        $friday_date = clone $date;
        if ($factory_id) {
            if (SewingHoliday::withoutGlobalScope('factoryId')
                ->whereDate('holiday', $date->toDateString())
                ->where('factory_id', $factory_id)->count()
            ) {
                $holiday = true;
            }/* elseif ($friday_date->isFriday()) {
                $holiday = true;
            }*/
            return $holiday;
        } else {
            if (SewingHoliday::whereDate('holiday', $date->toDateString())->count()) {
                $holiday = true;
            }/* elseif ($friday_date->isFriday()) {
                $holiday = true;
            }*/
            return $holiday;
        }
    }

    private function checkDateWorkingHour(Carbon $date, $factory_id = '')
    {
        if ($factory_id) {
            $sewing_working_hour_query = SewingWorkingHour::withoutGlobalScope('factoryId')
                ->where('factory_id', $factory_id)
                ->whereDate('working_date', $date->toDateString());
        } else {
            $sewing_working_hour_query = SewingWorkingHour::whereDate('working_date', $date->toDateString());
        }
        $sewing_working_hour = 10;
        if ($sewing_working_hour_query->count()) {
            $sewing_working_hour = $sewing_working_hour_query->first()->working_hour;
        }
        return $sewing_working_hour;
    }

    private function calculateEndDateForSewingPlan(Carbon $start_date_time, $allocated_qty, $line_id, $smv, $factory_id = "")
    {
        $start_time = clone $start_date_time;
        if ($factory_id) {
            $sewing_line_capacity = SewingLineCapacity::withoutGlobalScope('factoryId')
                ->where(['line_id' => $line_id, 'factory_id' => $factory_id])->first();
        } else {
            $sewing_line_capacity = SewingLineCapacity::where('line_id', $line_id)->first();
        }
        $capacity_required_minutes = $allocated_qty * $smv;
        $capacity_available_minutes = $sewing_line_capacity->capacity_available_minutes ?? 0;
        $line_working_hour = $sewing_line_capacity->working_hour ?? 0;
        if (!$capacity_available_minutes) {
            $data = [
                'status' => 'error',
                'start_date' => $start_time->toDateString(),
                'start_time' => $start_time->toTimeString(),
                'error' => "Capacity minutes is zero for this line. Cannot calculate end date time."
            ];

            return $data;
        } elseif (!$line_working_hour) {
            $data = [
                'status' => 'error',
                'start_date' => $start_time->toDateString(),
                'start_time' => $start_time->toTimeString(),
                'error' => "Working hour is zero for this line. Cannot calculate end date time."
            ];

            return $data;
        }
        $required_days = $capacity_available_minutes > 0 ? $capacity_required_minutes / $capacity_available_minutes : 0;
        $required_working_seconds = $required_days * $line_working_hour * 3600;
        $temp_required_working_seconds = $required_working_seconds;
        $temp_start_date = clone $start_date_time;
        $temp_start_time = $start_time->toTimeString();

        while ($required_working_seconds > 0) {
            $day_start_time = $temp_start_time;
            $this_date = clone $temp_start_date;
            $check_start_date_is_holiday = $factory_id ? $this->checkDateIsHoliday($temp_start_date, $factory_id) : $this->checkDateIsHoliday($temp_start_date);
            if ($check_start_date_is_holiday) {
                $temp_start_date->addDay(1);
                continue;
            }
            $check_this_date_working_hour = $factory_id ? $this->checkDateWorkingHour($temp_start_date, $factory_id) : $this->checkDateWorkingHour($temp_start_date);
            $this_date_available_working_seconds = $check_this_date_working_hour * 3600;
            $date_work_start_time = Carbon::parse($this_date->toDateString() . ' ' . date('H:i:s', strtotime("08:00:00")));
            $date_work_end_time = Carbon::parse($this_date->toDateString() . ' ' . date('H:i:s', strtotime("08:00:00")))->addSeconds($this_date_available_working_seconds);
            $plan_start_time = Carbon::parse($this_date->toDateString() . ' ' . $day_start_time);


            if ($plan_start_time > $date_work_start_time) {
                $this_date_available_working_seconds = $plan_start_time->diffInSeconds($date_work_end_time);
            }
            $temp_start_date = Carbon::parse($temp_start_date->toDateString() . ' ' . $plan_start_time->toTimeString());

            if ($this_date_available_working_seconds <= $required_working_seconds) {
                $end_date_time = $temp_start_date->addDay(1);
                $temp_start_time = date('H:i:s', strtotime("08:00:00"));
            } else {
                $end_date_time = $temp_start_date->addSeconds($required_working_seconds);
            }
            $required_working_seconds -= $this_date_available_working_seconds;
        }

        $last_date_working_hour = $check_this_date_working_hour;
        $required_full_days = (int)$required_days;
        $last_day = $required_days - $required_full_days;
        $last_day_working_hours = ($last_day - (int)$last_day) * $last_date_working_hour;
        $last_day_working_mins = ($last_day_working_hours - (int)$last_day_working_hours) * 60;
        $last_day_working_seconds = ($last_day_working_mins - (int)$last_day_working_mins) * 60;
        $end_date = clone $end_date_time;
        $end_date = $end_date->toDateString();
        $end_time = clone $end_date_time;
        $end_time = $end_time->toTimeString();

        $sample_data = [
            $start_date_time,
            $required_days,
            $temp_required_working_seconds,
            $last_day,
            $last_date_working_hour,
            $required_full_days . ' D',
            (int)$last_day_working_hours . ' H',
            (int)$last_day_working_mins . ' m',
            $last_day_working_seconds . ' s',
            $end_date_time
        ];

        $data = [
            'status' => 'success',
            'start_date' => $start_time->toDateString(),
            'start_time' => $start_time->toTimeString(),
            'end_date' => $end_date,
            'end_time' => $end_time,
            'total_required_seconds' => $temp_required_working_seconds,
            'sample_data' => $sample_data,
        ];

        return $data;
    }

    public function sewingPlanEventCreate(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'floor_id' => 'required',
            'line_id' => 'required',
            'purchase_order_id.*' => 'required',
            'allocated_qty.*' => 'required|min:0|not_in:0',
            'master_allocated_qty' => 'required|min:0|not_in:0',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date' => 'required',
            'end_time' => 'required',
        ], [
            'floor_id.required' => 'Floor is required',
            'line_id.required' => 'Line is required',
            'purchase_order_id.*.required' => 'PO is required',
            'allocated_qty.*.required' => 'Allocated qty is required.',
            'allocated_qty.*.min' => 'Negative value not acceptable.',
            'allocated_qty.*.not_in' => 'Must be greater than zero.',
            'master_allocated_qty.required' => 'Total Allocated qty is required.',
            'master_allocated_qty.min' => 'Negative value not acceptable.',
            'master_allocated_qty.not_in' => 'Must be greater than zero.',
            'start_date.required' => 'Start date is required',
            'start_time.required' => 'Start time is required',
            'end_date.required' => 'End date is required',
            'end_time.required' => 'End time is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();
            $start_date_time = Carbon::parse($request->start_date . ' ' . $request->start_time);
            $end_date_time = Carbon::parse($request->end_date . ' ' . $request->end_time);
            $buyer = Buyer::findOrFail($request->buyer_id)->name;
            $order = Order::findOrFail($request->order_id)->style_name;
            $sewing_plan = new SewingPlan();
            $sewing_plan->buyer_id = $request->buyer_id;
            $sewing_plan->order_id = $request->order_id;
            $sewing_plan->garments_item_id = $request->garments_item_id;
            $sewing_plan->smv = $request->smv;
            $sewing_plan->floor_id = $request->floor_id;
            $sewing_plan->line_id = $request->line_id;
            $sewing_plan->section_id = $request->line_id;
            $sewing_plan->allocated_qty = $request->master_allocated_qty;
            $sewing_plan->start_date = $start_date_time;
            $sewing_plan->end_date = $end_date_time;
            $sewing_plan->required_seconds = $request->required_seconds ?? 0;
            $sewing_plan->text = '<div class="event-progress">' . preg_replace('/\s+/', '', $buyer) . '/' . preg_replace('/\s+/', '', $order) . '</div>';
            $sewing_plan->plan_text = preg_replace('/\s+/', '', $buyer) . '/' . preg_replace('/\s+/', '', $order);
            $sewing_plan->board_color = $this->randomColor();
            $sewing_plan->save();

            $purchase_order_ids = $request->purchase_order_id;
            foreach ($purchase_order_ids as $key => $purchase_order_id) {
                $sewing_plan_detail = new SewingPlanDetail();
                $sewing_plan_detail->sewing_plan_id = $sewing_plan->id;
                $sewing_plan_detail->garments_item_id = $request->garments_item_id;
                $sewing_plan_detail->purchase_order_id = $request->purchase_order_id[$key];
                $sewing_plan_detail->allocated_qty = $request->allocated_qty[$key];
                $sewing_plan_detail->save();
            }
            DB::commit();

            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Data updated successfully!!"
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!"
            ])->render();

            return response()->json([
                'status' => 'danger',
                'error' => $e->getMessage(),
                'errors' => null,
                'message' => $html
            ]);
        }
    }

    public function sewingPlanNoteUpdate($id, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'notes' => 'required|max:191',
        ], [
            'notes.required' => 'Note is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();
            $sewing_plan = SewingPlan::findOrFail($id);
            $sewing_plan->notes = $request->notes;
            $sewing_plan->save();
            DB::commit();

            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Data updated successfully!!"
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!"
            ])->render();

            return response()->json([
                'status' => 'danger',
                'error' => $e->getMessage(),
                'errors' => null,
                'message' => $html
            ]);
        }
    }

    public function sewingPlanSplit(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'floor_id' => 'required',
            'line_id' => 'required',
            'master_split_qty' => 'required|integer|min:1',
            'sewing_plan_detail_id.*' => 'required',
            'purchase_order_id.*' => 'required',
            'split_qty.*' => 'required|integer|min:0',
            'previous_allocated_qty.*' => 'required|integer|min:0',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date' => 'required',
            'end_time' => 'required',
        ], [
            'floor_id.required' => 'Floor is required',
            'line_id.required' => 'Line is required',
            'master_split_qty.required' => 'Total Allocated qty is required.',
            'master_split_qty.min' => 'Negative value not acceptable.',
            'master_split_qty.integer' => 'Must be integer.',
            'sewing_plan_detail_id.*.required' => 'Sewing plan detail id is required.',
            'purchase_order_id.*.required' => 'Purchase Order id is required.',
            'split_qty.*.required' => 'Split qty is required.',
            'split_qty.*.min' => 'Negative value not acceptable.',
            'split_qty.*.integer' => 'Must be integer.',
            'previous_allocated_qty.*.required' => 'Previous allocated qty is required.',
            'previous_allocated_qty.*.min' => 'Negative value not acceptable.',
            'previous_allocated_qty.*.integer' => 'Must be integer.',
            'start_date.required' => 'Start date is required',
            'start_time.required' => 'Start time is required',
            'end_date.required' => 'End date is required',
            'end_time.required' => 'End time is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();
            $previous_allocated_qty = array_sum($request->previous_allocated_qty);
            $sewing_plan_detail_ids = $request->sewing_plan_detail_id;

            $sewing_plan = SewingPlan::findOrFail($request->id);
            $old_sewing_plan_buyer_id = $sewing_plan->buyer_id;
            $old_sewing_plan_order_id = $sewing_plan->order_id;
            $old_sewing_plan_garments_item_id = $sewing_plan->garments_item_id;
            $old_sewing_plan_smv = $sewing_plan->smv;
            $old_sewing_plan_buyer_name = $sewing_plan->buyer->name;
            $old_sewing_plan_style_name = $sewing_plan->order->style_name;

            if ($previous_allocated_qty == 0) {
                $sewing_plan->delete();
            } else {
                $start_date_of_previous_plan = Carbon::parse($sewing_plan->start_date);
                $old_plan_calculation = $this->calculateEndDateForSewingPlan($start_date_of_previous_plan, $previous_allocated_qty, $sewing_plan->line_id, $request->smv);

                $sewing_plan->end_date = Carbon::parse($old_plan_calculation['end_date'] . ' ' . $old_plan_calculation['end_time']);
                $sewing_plan->required_seconds = $old_plan_calculation['total_required_seconds'];
                $sewing_plan->allocated_qty = $previous_allocated_qty;
                $sewing_plan->save();

                foreach ($sewing_plan_detail_ids as $key => $sewing_plan_detail_id) {
                    $old_sewing_plan_detail = SewingPlanDetail::findOrFail($sewing_plan_detail_id);
                    if ($request->previous_allocated_qty[$key] == 0) {
                        $old_sewing_plan_detail->delete();
                    } elseif ($request->previous_allocated_qty[$key] > 0) {
                        $old_sewing_plan_detail->allocated_qty = $request->previous_allocated_qty[$key];
                        $old_sewing_plan_detail->save();
                    }
                }
            }

            $start_date_time = Carbon::parse($request->start_date . ' ' . $request->start_time);
            $end_date_time = Carbon::parse($request->end_date . ' ' . $request->end_time);

            $splitted_sewing_plan = new SewingPlan();
            $splitted_sewing_plan->buyer_id = $old_sewing_plan_buyer_id;
            $splitted_sewing_plan->order_id = $old_sewing_plan_order_id;
            $splitted_sewing_plan->garments_item_id = $old_sewing_plan_garments_item_id;
            $splitted_sewing_plan->smv = $old_sewing_plan_smv;
            $splitted_sewing_plan->floor_id = $request->floor_id;
            $splitted_sewing_plan->line_id = $request->line_id;
            $splitted_sewing_plan->section_id = $request->line_id;
            $splitted_sewing_plan->allocated_qty = $request->master_split_qty;
            $splitted_sewing_plan->start_date = $start_date_time;
            $splitted_sewing_plan->end_date = $end_date_time;
            $splitted_sewing_plan->required_seconds = $request->required_seconds;
            $splitted_sewing_plan->text = '<div class="event-progress">' . preg_replace('/\s+/', '', $old_sewing_plan_buyer_name) . '/' . preg_replace('/\s+/', '', $old_sewing_plan_style_name) . '</div>';
            $splitted_sewing_plan->plan_text = preg_replace('/\s+/', '', $old_sewing_plan_buyer_name) . '/' . preg_replace('/\s+/', '', $old_sewing_plan_style_name);
            $splitted_sewing_plan->board_color = $this->randomColor();
            $splitted_sewing_plan->save();

            foreach ($sewing_plan_detail_ids as $key => $sewing_plan_detail_id) {
                if ($request->split_qty[$key] > 0) {
                    $splitted_sewing_plan_detail = new SewingPlanDetail();
                    $splitted_sewing_plan_detail->sewing_plan_id = $splitted_sewing_plan->id;
                    $splitted_sewing_plan_detail->garments_item_id = $old_sewing_plan_garments_item_id;
                    $splitted_sewing_plan_detail->purchase_order_id = $request->purchase_order_id[$key];
                    $splitted_sewing_plan_detail->allocated_qty = $request->split_qty[$key];
                    $splitted_sewing_plan_detail->save();
                } else {
                    continue;
                }
            }
            DB::commit();
            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Data updated successfully!!"
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!"
            ])->render();

            return response()->json([
                'status' => 'danger',
                'error' => $e->getMessage(),
                'errors' => null,
                'message' => $html
            ]);
        }
    }

    public function getSewingPlanOrderDetails($id)
    {
        $sewing_plan = SewingPlan::with('buyer:id,name', 'order:id,reference_no,style_name,repeat_no', 'floor:id,floor_no', 'line:id,line_no,floor_id')->findOrFail($id);
        $floors = Floor::pluck('floor_no', 'id');
        $html = view('sewingdroplets::pages.order_details_for_sewing_plan', [
            'sewing_plan' => $sewing_plan,
        ])->render();

        $split_qty_html = view('sewingdroplets::pages.split_qty_for_sewing_plan', [
            'sewing_plan' => $sewing_plan,
            'floors' => $floors
        ])->render();

        $change_line_html = view('sewingdroplets::pages.change_line_for_sewing_plan', [
            'sewing_plan' => $sewing_plan,
            'floors' => $floors
        ])->render();

        $lock_unlock_html = view('sewingdroplets::pages.lock_unlock_sewing_plan_strip', [
            'sewing_plan' => $sewing_plan,
        ])->render();

        return response()->json([
            'html' => $html,
            'split_qty_html' => $split_qty_html,
            'change_line_html' => $change_line_html,
            'lock_unlock_html' => $lock_unlock_html,
        ]);
    }

    public function sewingPlanStripLockUnlock($id, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'is_locked' => 'required',
        ], [
            'is_locked.required' => 'Lock status is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }
        try {
            DB::beginTransaction();
            $sewing_plan = SewingPlan::findOrfail($id);
            $sewing_plan->is_locked = $request->is_locked;
            $sewing_plan->save();
            DB::commit();

            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Data updated successfully!!"
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!"
            ])->render();

            return response()->json([
                'status' => 'danger',
                'error' => $e->getMessage(),
                'errors' => null,
                'message' => $html
            ]);
        }
    }

    public function getSewingFloorsForFactory($factory_id)
    {
        return Floor::withoutGlobalScopes()
            ->where('factory_id', $factory_id)
            ->whereNull('deleted_at')
            ->pluck('floor_no', 'id');
    }

    public function getOrdersForFactory($factory_id)
    {
        return Order::withoutGlobalScopes()
            ->whereNull('deleted_at')
            ->where('factory_id', $factory_id)
            ->pluck('order_style_no', 'id');
    }

    public function getOrderList($buyer_id)
    {
        $orders_query = Order::withoutGlobalScopes()->whereNull('deleted_at')->where('buyer_id', $buyer_id);
        $response = 500;
        $orders = [];
        if ($orders_query->count()) {
            $orders = $orders_query->get();
            $response = 200;
        }
        return response()->json([
            'status' => $response,
            'orders' => $orders,
        ]);
    }

    public function getPurchaseOrderList($order_id)
    {
        return PurchaseOrder::withoutGlobalScopes()->where('order_id', $order_id)->whereNull('deleted_at')->pluck('po_no', 'id');
    }

    public function getPurchaseOrderInfo($purchase_order_id)
    {
        return PurchaseOrder::with('po_details.countries')->withoutGlobalScopes()->findOrFail($purchase_order_id);
    }

    public function getSewingLineForSewingPlan($id)
    {
        return Line::where('id', $id)->pluck('line_no', 'id');
    }

    public function index($user_id, $factory_id, Request $request)
    {
        if ($request->has('set_date')) {
            $from = Carbon::parse($request->set_date)->subMonth()->startOfMonth()->toDateString();
            $to = Carbon::parse($request->set_date)->addMonth()->endOfMonth()->toDateString();
        } else {
            $from = $request->from ?? Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $to = $request->to ?? Carbon::now()->addMonth()->endOfMonth()->toDateString();
        }

        $sewing_plans = SewingPlan::with('sewingPlanDetailsWithOutGlobalScope.purchaseOrder', 'order')
            ->withoutGlobalScope('factoryId')
            ->where('sewing_plans.factory_id', $factory_id)
            ->whereDate("sewing_plans.start_date", "<", $to)
            ->whereDate("sewing_plans.end_date", ">=", $from)
            ->whereNull('sewing_plans.deleted_at')
            ->select('sewing_plans.*')
            ->get();
        foreach ($sewing_plans as $key => $sewing_plan) {
            $sewing_plans[$key]['production'] = DB::table('sewingoutputs')
                ->join('bundle_cards', 'bundle_cards.id', 'sewingoutputs.bundle_card_id')
                ->where([
                    'sewingoutputs.factory_id' => $factory_id,
                    'sewingoutputs.line_id' => $sewing_plan->line_id,
                ])
                ->whereIn('sewingoutputs.purchase_order_id', $sewing_plan->sewingPlanDetailsWithOutGlobalScope->pluck('purchase_order_id')->toArray())
                ->where('sewingoutputs.created_at', '>=', $sewing_plan->start_date)
                ->where('sewingoutputs.created_at', '<=', $sewing_plan->end_date)
                ->whereNull('sewingoutputs.deleted_at')
                ->selectRaw('SUM(bundle_cards.quantity - bundle_cards.total_rejection - bundle_cards.sewing_rejection) - SUM(IF(bundle_cards.print_rejection > bundle_cards.embroidary_rejection, bundle_cards.print_rejection, COALESCE(bundle_cards.embroidary_rejection, 0))) as production')
                ->first()->production ?? 0;
            $sewing_plans[$key]['ex_factory_date'] = $sewing_plan->sewingPlanDetailsWithOutGlobalScope->first()->purchaseOrder->ex_factory_date;
        }

        $lines_query = SewingLineCapacity::withoutGlobalScope('factoryId')
            ->leftJoin('lines', 'lines.id', 'sewing_line_capacities.line_id')
            ->leftJoin('floors', 'floors.id', 'sewing_line_capacities.floor_id')
            ->where('sewing_line_capacities.factory_id', $factory_id)
            ->whereNull('sewing_line_capacities.deleted_at')
            ->orderBy('sewing_line_capacities.floor_id')
            ->select('lines.*', 'floors.floor_no')
            ->get();

        $sewing_holiday_query = SewingHoliday::withoutGlobalScope('factoryId')->where('factory_id', $factory_id)->get();
        $sewing_holidays = [];
        foreach ($sewing_holiday_query as $sewing_holiday) {
            $sewing_holidays[] = [
                'start_date' => Carbon::parse($sewing_holiday->holiday)->startOfDay()->toDateTimeString(),
                'end_date' => Carbon::parse($sewing_holiday->holiday)->endOfDay()->toDateTimeString()
            ];
        }
        $lines = [];
        foreach ($lines_query->sortBy('sort') as $line) {
            $lines[] = ['key' => $line->id, 'label' => $line->line_no, 'line' => $line];
        }

        $board_colors = [];
        $boardColorQuery = clone $sewing_plans;
        $boardColorQuery->each(function ($item, $i) use (&$board_colors, $factory_id) {
            $board_colors[] = [
                'key' => $item->id,
                'label' => $item->text,
                'plan_date' => Carbon::parse($item->end_date)->toDateString(),
                'plan_start_date' => Carbon::parse($item->start_date)->toDateString(),
                'backgroundColor' => $item->board_color,
                'plan_qty' => $item->allocated_qty,
                'production' => $item->production,
                'ex_factory_date' => $item->ex_factory_date,
            ];
        });

        return response()->json([
            "data" => $sewing_plans,
            "collections" => [
                "sections" => $lines,
                'lines' => $lines,
                'sewing_holidays' => $sewing_holidays,
                "board_colors" => $board_colors,
            ]
        ]);
    }

    public function update($user_id, $factory_id, $id, Request $request)
    {
        try {
            DB::beginTransaction();
            $start_date = Carbon::parse($request->start_date);
            $line_id = $request->section_id;
            $floor_id = Line::withoutGlobalScope('factoryId')->findOrFail($line_id)->floor_id;
            $allocated_qty = $request->allocated_qty;
            $smv = SewingPlan::withoutGlobalScope('factoryId')->where('id', $id)->first()->smv;
            $end_date = null;
            $check_start_date_is_holiday = $this->checkDateIsHoliday($start_date, $factory_id);
            if ($check_start_date_is_holiday) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Start date cannot be a holiday!!',
                    "tid" => $id,
                ]);
            }
            $check_if_already_plan_exist_in_this_date = $this->checkPlanExistInThisDate($start_date, $line_id, $factory_id, $id);
            if ($check_if_already_plan_exist_in_this_date['status']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Plan already exists in this day!!',
                    "tid" => $id,
                ]);
            }

            $start_time = $check_if_already_plan_exist_in_this_date['plan_start_time'] ? date('H:i:s', strtotime($check_if_already_plan_exist_in_this_date['plan_start_time'])) : date('H:i:s', strtotime('08:00:00'));
            $start_date = Carbon::parse($start_date->toDateString() . ' ' . $start_time);
            $new_plan_calculation = $this->calculateEndDateForSewingPlan($start_date, $allocated_qty, $line_id, $smv, $factory_id);
            if ($new_plan_calculation['status'] == 'error') {
                $action = "error";
                return response()->json([
                    "status" => $action,
                    "tid" => $id,
                    "message" => $new_plan_calculation['error']
                ]);
            }

            $end_date = Carbon::parse($new_plan_calculation['end_date'] . ' ' . $new_plan_calculation['end_time']);
            $check_if_plan_exist_in_date_range = $this->checkIfPlanExistInDateRange($start_date, $end_date, $id, $line_id, $factory_id);
            $total_required_seconds = $new_plan_calculation['total_required_seconds'];

            if ($check_if_plan_exist_in_date_range) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Plan already exists in this range!!',
                    "tid" => $id,
                ]);
            };

            $sewing_plan = [
                'floor_id' => $floor_id,
                'line_id' => $line_id,
                'section_id' => $line_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'required_seconds' => $total_required_seconds,
                'updated_by' => $user_id,
                'updated_at' => Carbon::now(),
            ];
            $old_sewing_plan_data = DB::table('sewing_plans')->where('id', $id)->first();
            $this->updateUndoRedoSewingPlanForUndoData($old_sewing_plan_data);
            $update = DB::table('sewing_plans')->where('id', $id)->update($sewing_plan);

            if ($update) {
                $action = "updated";
                DB::commit();
                return response()->json([
                    "action" => $action,
                    "tid" => $id,
                    'status' => 'success',
                    'message' => 'Plan updated successfully!!',
                ]);
            } else {
                DB::rollback();
                $action = "error";
                return response()->json([
                    "action" => $action,
                    "tid" => $id,
                    'status' => 'error',
                    'message' => 'Something went wrong!!',
                ]);
            }
        } catch (Excception $e) {
            DB::rollback();
            $action = "error";
            return response()->json([
                "action" => $action,
                "tid" => $id,
                "error" => $e->getMessage(),
                'message' => 'Something went wrong!!',
            ]);
        }
    }

    public function sewingPlanLineChange(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'floor_id' => 'required',
            'line_id' => 'required',
            'master_allocated_qty' => 'required|integer|min:1',
            'sewing_plan_detail_id.*' => 'required',
            'purchase_order_id.*' => 'required',
            'allocated_qty.*' => 'required|integer|min:0',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date' => 'required',
            'end_time' => 'required',
        ], [
            'floor_id.required' => 'Floor is required',
            'line_id.required' => 'Line is required',
            'master_allocated_qty.required' => 'Total Allocated qty is required.',
            'master_allocated_qty.min' => 'Negative value not acceptable.',
            'master_allocated_qty.integer' => 'Must be integer.',
            'sewing_plan_detail_id.*.required' => 'Sewing plan detail id is required.',
            'purchase_order_id.*.required' => 'Purchase Order id is required.',
            'allocated_qty.*.required' => 'Split qty is required.',
            'allocated_qty.*.min' => 'Negative value not acceptable.',
            'allocated_qty.*.integer' => 'Must be integer.',
            'start_date.required' => 'Start date is required',
            'start_time.required' => 'Start time is required',
            'end_date.required' => 'End date is required',
            'end_time.required' => 'End time is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();
            $id = $request->id;
            $line_id = $request->line_id;
            $start_date_time = Carbon::parse($request->start_date . ' ' . $request->start_time);
            $end_date_time = Carbon::parse($request->end_date . ' ' . $request->end_time);

            $sewing_plan = SewingPlan::findOrFail($request->id);
            $factory_id = $sewing_plan->factory_id;
            $check_if_plan_exist_in_date_range = $this->checkIfPlanExistInDateRange($start_date_time, $end_date_time, $id, $line_id, $factory_id);

            if ($check_if_plan_exist_in_date_range) {
                $html = view('partials.flash_message', [
                    'message_class' => "danger",
                    'message' => "Plan already exists in this range!!"
                ])->render();

                return response()->json([
                    'status' => 'danger',
                    'error' => null,
                    'errors' => null,
                    'message' => $html
                ]);
            };

            $old_plan_data = clone $sewing_plan;
            $sewing_plan->floor_id = $request->floor_id;
            $sewing_plan->line_id = $request->line_id;
            $sewing_plan->section_id = $request->line_id;
            $sewing_plan->start_date = $start_date_time;
            $sewing_plan->end_date = $end_date_time;
            $sewing_plan->required_seconds = $request->required_seconds ?? 0;
            $sewing_plan->save();
            $this->updateUndoRedoSewingPlanForUndoData($old_plan_data);
            DB::commit();
            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Data updated successfully!!"
            ])->render();

            return response()->json([
                'status' => 'success',
                'errors' => null,
                'message' => $html,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            $html = view('partials.flash_message', [
                'message_class' => "danger",
                'message' => "Something went wrong!!"
            ])->render();

            return response()->json([
                'status' => 'danger',
                'error' => $e->getMessage(),
                'errors' => null,
                'message' => $html
            ]);
        }
    }

    private function checkIfPlanExistInDateRange(Carbon $start_date, Carbon $end_date, $requested_id, $line_id, $factory_id)
    {
        $sewing_plan_query = DB::table('sewing_plans')
            ->where([
                'line_id' => $line_id,
                'factory_id' => $factory_id,
            ])
            ->whereNull('deleted_at')
            ->where('id', '!=', $requested_id)
            ->where('start_date', '<', $end_date->toDateTimeString())
            ->where('end_date', '>', $start_date->toDateTimeString());
        return $sewing_plan_query->count();
    }

    public function sewingPlanDelete($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $sewing_plan = SewingPlan::findOrFail($id);
            $sewing_plan->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Plan unloaded successfully!!",
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'danger',
                'errors' => null,
                'message' => "Something went wrong!!"
            ]);
        }
    }

    public function pullStrip(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $id = $request->id;
            $sewing_plan = SewingPlan::findOrFail($id);
            $old_sewing_plan_data = clone $sewing_plan;
            $current_sewing_plan_start_date = Carbon::parse($sewing_plan->start_date)->toDateString();
            $allocated_qty = $sewing_plan->allocated_qty;
            $smv = $sewing_plan->first()->smv;
            $factory_id = $sewing_plan->factory_id;

            $previous_sewing_plans_query = SewingPlan::where('end_date', '<', $sewing_plan->start_date)
                ->where(['line_id' => $sewing_plan->line_id])
                ->orderBy('end_date', 'desc');
            if ($previous_sewing_plans_query->count() <= 0) {
                return response()->json([
                    'status' => 'error',
                    'error' => null,
                    'message' => 'No previous plan found!!!'
                ]);
            }
            $previous_sewing_plan = $previous_sewing_plans_query->first();

            $new_start_date = Carbon::parse($previous_sewing_plan->start_date);
            $line_id = $previous_sewing_plan->line_id;
            $plan_exist_status = 1;
            while ($plan_exist_status > 0) {
                $check_if_already_plan_exist_in_this_date = $this->checkPlanExistInThisDate($new_start_date, $line_id);
                $plan_exist_status = $check_if_already_plan_exist_in_this_date['status'];
                if ($plan_exist_status) {
                    $new_start_date = $new_start_date->addDay(1);
                    if ($new_start_date->toDateString() >= $current_sewing_plan_start_date) {
                        return response()->json([
                            'status' => 'error',
                            'error' => null,
                            'message' => 'Sorry!! Cannot pull this strip!!'
                        ]);
                    }
                }
            };

            $start_time = $check_if_already_plan_exist_in_this_date['plan_start_time'] ? date('H:i:s', strtotime($check_if_already_plan_exist_in_this_date['plan_start_time'])) : date('H:i:s', strtotime('08:00:00'));
            $start_date = Carbon::parse($new_start_date->toDateString() . ' ' . $start_time);
            $new_plan_calculation = $this->calculateEndDateForSewingPlan($start_date, $allocated_qty, $line_id, $smv);

            if ($new_plan_calculation['status'] == 'error') {
                return response()->json([
                    'status' => 'error',
                    'error' => null,
                    'message' => $new_plan_calculation['error'],
                ]);
            }
            $end_date = Carbon::parse($new_plan_calculation['end_date'] . ' ' . $new_plan_calculation['end_time']);
            $check_if_plan_exist_in_date_range = $this->checkIfPlanExistInDateRange($start_date, $end_date, $id, $line_id, $factory_id);

            if ($check_if_plan_exist_in_date_range) {
                return response()->json([
                    'status' => 'error',
                    'error' => null,
                    'message' => 'Plan already exists in this range!!',
                ]);
            };

            $sewing_plan->start_date = $start_date;
            $sewing_plan->end_date = $end_date;
            $sewing_plan->save();
            $this->updateUndoRedoSewingPlanForUndoData($old_sewing_plan_data);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'error' => null,
                'message' => 'Plan updated successfully!!!'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'danger',
                'error' => $e->getMessage(),
                'message' => 'Something went wrong!!!'
            ]);
        }
    }

    public function pushStrip(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $id = $request->id;
            $sewing_plan = SewingPlan::findOrFail($id);
            $old_sewing_plan_data = clone $sewing_plan;
            $current_sewing_plan_start_date = Carbon::parse($sewing_plan->start_date);
            $allocated_qty = $sewing_plan->allocated_qty;
            $smv = $sewing_plan->first()->smv;
            $factory_id = $sewing_plan->factory_id;

            $next_sewing_plans_query = SewingPlan::where('start_date', '>', $sewing_plan->end_date)
                ->where(['line_id' => $sewing_plan->line_id])
                ->orderBy('start_date', 'asc');
            if ($next_sewing_plans_query->count() <= 0) {
                return response()->json([
                    'status' => 'error',
                    'error' => null,
                    'message' => 'No next plan found!!!'
                ]);
            }
            $next_sewing_plan = $next_sewing_plans_query->first();

            $next_plan_start_date = Carbon::parse($next_sewing_plan->start_date)->toDateString();
            $new_start_date = Carbon::parse($next_plan_start_date);
            $line_id = $next_sewing_plan->line_id;
            $plan_exist_status = 1;
            while ($plan_exist_status > 0) {
                $check_if_already_plan_exist_in_this_date = $this->checkPlanExistInThisDate($new_start_date, $line_id);
                $plan_exist_status = $check_if_already_plan_exist_in_this_date['status'];
                if ($plan_exist_status) {
                    $new_start_date = $new_start_date->subDay(1);
                    if ($new_start_date->toDateString() <= $current_sewing_plan_start_date->toDateString()) {
                        return response()->json([
                            'status' => 'error',
                            'error' => null,
                            'message' => 'Sorry!! Cannot push this strip!!'
                        ]);
                    }
                } else {
                    $start_time = $check_if_already_plan_exist_in_this_date['plan_start_time'] ? date('H:i:s', strtotime($check_if_already_plan_exist_in_this_date['plan_start_time'])) : date('H:i:s', strtotime('08:00:00'));
                    $new_plan_start_date = Carbon::parse($new_start_date->toDateString() . ' ' . $start_time);
                    if ($next_plan_start_date <= $new_plan_start_date) {
                        $new_start_date = $new_start_date->subDay(1);
                        $plan_exist_status = 1;
                    }
                }
            };

            $start_time = $check_if_already_plan_exist_in_this_date['plan_start_time'] ? date('H:i:s', strtotime($check_if_already_plan_exist_in_this_date['plan_start_time'])) : date('H:i:s', strtotime('08:00:00'));
            $start_date = Carbon::parse($new_start_date->toDateString() . ' ' . $start_time);
            $new_plan_calculation = $this->calculateEndDateForSewingPlan($start_date, $allocated_qty, $line_id, $smv);
            if ($new_plan_calculation['status'] == 'error') {
                return response()->json([
                    'status' => 'error',
                    'error' => null,
                    'message' => $new_plan_calculation['error'],
                ]);
            }
            $end_date = Carbon::parse($new_plan_calculation['end_date'] . ' ' . $new_plan_calculation['end_time']);
            $check_if_plan_exist_in_date_range = $this->checkIfPlanExistInDateRange($start_date, $end_date, $id, $line_id, $factory_id);

            while ($check_if_plan_exist_in_date_range) {
                $new_start_date = $new_start_date->subDay(1);
                if ($new_start_date->toDateString() <= $current_sewing_plan_start_date->toDateString()) {
                    return response()->json([
                        'status' => 'error',
                        'error' => null,
                        'message' => 'Sorry!! Cannot push this strip!!'
                    ]);
                }
                $check_if_already_plan_exist_in_this_date = $this->checkPlanExistInThisDate($new_start_date, $line_id);
                $start_time = $check_if_already_plan_exist_in_this_date['plan_start_time'] ? date('H:i:s', strtotime($check_if_already_plan_exist_in_this_date['plan_start_time'])) : date('H:i:s', strtotime('08:00:00'));
                $start_date = Carbon::parse($new_start_date->toDateString() . ' ' . $start_time);
                $new_plan_calculation = $this->calculateEndDateForSewingPlan($start_date, $allocated_qty, $line_id, $smv);
                if ($new_plan_calculation['status'] == 'error') {
                    return response()->json([
                        'status' => 'error',
                        'error' => null,
                        'message' => $new_plan_calculation['error'],
                    ]);
                }
                $end_date = Carbon::parse($new_plan_calculation['end_date'] . ' ' . $new_plan_calculation['end_time']);
                $check_if_plan_exist_in_date_range = $this->checkIfPlanExistInDateRange($start_date, $end_date, $id, $line_id, $factory_id);
            }

            if ($check_if_plan_exist_in_date_range) {
                return response()->json([
                    'status' => 'error',
                    'error' => null,
                    'message' => 'Plan already exists in this range!!',
                ]);
            };

            $sewing_plan->start_date = $start_date;
            $sewing_plan->end_date = $end_date;
            $sewing_plan->save();
            $this->updateUndoRedoSewingPlanForUndoData($old_sewing_plan_data);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'error' => null,
                'message' => 'Plan updated successfully!!!'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'danger',
                'error' => $e->getMessage(),
                'message' => 'Something went wrong!!!'
            ]);
        }
    }

    private function checkOtherRunningPlans($requested_id, Carbon $start_date, Carbon $end_date, $line_id, $factory_id)
    {
        $sewing_plan_query = DB::table('sewing_plans')
            ->where([
                'line_id' => $line_id,
                'factory_id' => $factory_id,
            ])
            ->whereNull('deleted_at')
            ->where('id', '!=', $requested_id)
            ->where('start_date', '<', $end_date->toDateTimeString())
            ->where('end_date', '>', $start_date->toDateTimeString());
        $sewing_plan_count = $sewing_plan_query->count();

        /*if ($sewing_plan_count) {
            $sewing_plans = $sewing_plan_query->orderBy('start_date', 'asc')->get();
            foreach ($sewing_plans as $sewing_plan) {
                $sewing_plan_id = $sewing_plan->id;
                if ($sewing_plan_id == $requested_id) {
                    continue;
                }
                $start_date = Carbon::parse($sewing_plan->start_date);
                $end_date = Carbon::parse($sewing_plan->end_date);
                $this->updateNextPlansData($requested_id, $start_date, $end_date, $line_id, $sewing_plan, $factory_id);
            }
        }*/
        $data = [
            'sewing_plan_count' => $sewing_plan_count,
        ];

        return $data;
    }

    private function updateNextPlansData($requested_id, Carbon $start_date, Carbon $end_date, $line_id, $sewing_plan, $factory_id)
    {
        $update_plan_id = $sewing_plan->id;
        $update_plan_data = [
            'start_date' => $start_date->addMinute(1),
            'end_date' => $end_date->addSecond(1)
        ];
        DB::table('sewing_plans')->where('id', $update_plan_id)->update($update_plan_data);
        $sewing_updated_date = DB::table('sewing_plans')->where('id', $update_plan_id)->first();
        $this->checkOtherRunningPlans($sewing_plan->id, Carbon::parse($sewing_updated_date->start_date), Carbon::parse($sewing_updated_date->end_date), $line_id, $factory_id);
        return true;
    }

    public function randomColor()
    {
        return '#78909c';
        /* DO NOT DELETE THIS CODE
         * 
         * $color_array = ['#E1593A', '#008a00', '#0050ef', '#144B13', '#FF0B59', '#2AC0FF', '#44FF42'];
        $randIndex = array_rand($color_array);

        return $color_array[$randIndex];
        */
        //return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    private function updateUndoRedoSewingPlanForUndoData($old_sewing_plan_data)
    {
        $first_data = DB::table('undo_redo_sewing_plans')->whereNull('deleted_at')->first();
        $id = $first_data->id ?? '';
        $undo_redo_table_data = [
            'sewing_plan_id' => $old_sewing_plan_data->id,
            'buyer_id' => $old_sewing_plan_data->buyer_id,
            'order_id' => $old_sewing_plan_data->order_id,
            'purchase_order_id' => $old_sewing_plan_data->purchase_order_id,
            'floor_id' => $old_sewing_plan_data->floor_id,
            'line_id' => $old_sewing_plan_data->line_id,
            'section_id' => $old_sewing_plan_data->section_id,
            'allocated_qty' => $old_sewing_plan_data->allocated_qty,
            'start_date' => $old_sewing_plan_data->start_date,
            'end_date' => $old_sewing_plan_data->end_date,
            'required_seconds' => $old_sewing_plan_data->required_seconds,
            'text' => $old_sewing_plan_data->text,
            'plan_text' => $old_sewing_plan_data->plan_text,
            'progress' => $old_sewing_plan_data->progress,
            'is_locked' => $old_sewing_plan_data->is_locked,
            'board_color' => $old_sewing_plan_data->board_color,
            'notes' => $old_sewing_plan_data->notes,
            'factory_id' => $old_sewing_plan_data->factory_id,
            'undo_redo_status' => UNDO_SEWING_PLAN,
            'created_by' => $old_sewing_plan_data->created_by,
            'updated_by' => $old_sewing_plan_data->updated_by,
        ];

        if ($id) {
            DB::table('undo_redo_sewing_plans')->where('id', $id)->update($undo_redo_table_data);
        } else {
            DB::table('undo_redo_sewing_plans')->insert($undo_redo_table_data);
        }
    }

    public function undoSewingPlan(Request $request)
    {
        if (\Request::ajax()) {
            try {
                DB::beginTransaction();
                $temp_undo_data = UndoRedoSewingPlan::where('undo_redo_status', UNDO_SEWING_PLAN)->first();

                if (!$temp_undo_data) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Sorry nothing to undo!'
                    ]);
                }
                $temp_current_plan_data = SewingPlan::findOrFail($temp_undo_data->sewing_plan_id);
                if (!$temp_current_plan_data) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Sorry nothing to undo!'
                    ]);
                }
                $undo_redo_table_data = [
                    'sewing_plan_id' => $temp_current_plan_data->id,
                    'buyer_id' => $temp_current_plan_data->buyer_id,
                    'order_id' => $temp_current_plan_data->order_id,
                    'purchase_order_id' => $temp_current_plan_data->purchase_order_id,
                    'floor_id' => $temp_current_plan_data->floor_id,
                    'line_id' => $temp_current_plan_data->line_id,
                    'section_id' => $temp_current_plan_data->section_id,
                    'allocated_qty' => $temp_current_plan_data->allocated_qty,
                    'start_date' => $temp_current_plan_data->start_date,
                    'end_date' => $temp_current_plan_data->end_date,
                    'required_seconds' => $temp_current_plan_data->required_seconds,
                    'text' => $temp_current_plan_data->text,
                    'plan_text' => $temp_current_plan_data->plan_text,
                    'progress' => $temp_current_plan_data->progress,
                    'is_locked' => $temp_current_plan_data->is_locked,
                    'board_color' => $temp_current_plan_data->board_color,
                    'notes' => $temp_current_plan_data->notes,
                    'factory_id' => $temp_current_plan_data->factory_id,
                    'undo_redo_status' => REDO_SEWING_PLAN,
                    'created_by' => $temp_current_plan_data->created_by,
                    'updated_by' => $temp_current_plan_data->updated_by,
                ];

                $new_sewing_plan_data = [
                    'buyer_id' => $temp_undo_data->buyer_id,
                    'order_id' => $temp_undo_data->order_id,
                    'purchase_order_id' => $temp_undo_data->purchase_order_id,
                    'floor_id' => $temp_undo_data->floor_id,
                    'line_id' => $temp_undo_data->line_id,
                    'section_id' => $temp_undo_data->section_id,
                    'allocated_qty' => $temp_undo_data->allocated_qty,
                    'start_date' => $temp_undo_data->start_date,
                    'end_date' => $temp_undo_data->end_date,
                    'required_seconds' => $temp_undo_data->required_seconds,
                    'text' => $temp_undo_data->text,
                    'plan_text' => $temp_undo_data->plan_text,
                    'progress' => $temp_undo_data->progress,
                    'is_locked' => $temp_undo_data->is_locked,
                    'board_color' => $temp_undo_data->board_color,
                    'notes' => $temp_undo_data->notes,
                    'factory_id' => $temp_undo_data->factory_id,
                    'created_by' => $temp_undo_data->created_by,
                    'updated_by' => $temp_undo_data->updated_by,
                ];

                DB::table('undo_redo_sewing_plans')->where('id', $temp_undo_data->id)->update($undo_redo_table_data);
                DB::table('sewing_plans')->where('id', $temp_current_plan_data->id)->update($new_sewing_plan_data);
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Last plan data updated!'
                ]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong!'
                ]);
            }
        } else {
            abort('404');
        }
    }

    public function redoSewingPlan(Request $request)
    {
        if (\Request::ajax()) {
            try {
                DB::beginTransaction();
                $temp_redo_data = UndoRedoSewingPlan::where('undo_redo_status', REDO_SEWING_PLAN)->first();
                if (!$temp_redo_data) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Sorry nothing to redo!'
                    ]);
                }
                $temp_current_plan_data = SewingPlan::findOrFail($temp_redo_data->sewing_plan_id);
                if (!$temp_current_plan_data) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Sorry nothing to redo!'
                    ]);
                }
                $undo_redo_table_data = [
                    'sewing_plan_id' => $temp_current_plan_data->id,
                    'buyer_id' => $temp_current_plan_data->buyer_id,
                    'order_id' => $temp_current_plan_data->order_id,
                    'purchase_order_id' => $temp_current_plan_data->purchase_order_id,
                    'floor_id' => $temp_current_plan_data->floor_id,
                    'line_id' => $temp_current_plan_data->line_id,
                    'section_id' => $temp_current_plan_data->section_id,
                    'allocated_qty' => $temp_current_plan_data->allocated_qty,
                    'start_date' => $temp_current_plan_data->start_date,
                    'end_date' => $temp_current_plan_data->end_date,
                    'required_seconds' => $temp_current_plan_data->required_seconds,
                    'text' => $temp_current_plan_data->text,
                    'plan_text' => $temp_current_plan_data->plan_text,
                    'progress' => $temp_current_plan_data->progress,
                    'is_locked' => $temp_current_plan_data->is_locked,
                    'board_color' => $temp_current_plan_data->board_color,
                    'notes' => $temp_current_plan_data->notes,
                    'factory_id' => $temp_current_plan_data->factory_id,
                    'undo_redo_status' => UNDO_SEWING_PLAN,
                    'created_by' => $temp_current_plan_data->created_by,
                    'updated_by' => $temp_current_plan_data->updated_by,
                ];

                $new_sewing_plan_data = [
                    'buyer_id' => $temp_redo_data->buyer_id,
                    'order_id' => $temp_redo_data->order_id,
                    'purchase_order_id' => $temp_redo_data->purchase_order_id,
                    'floor_id' => $temp_redo_data->floor_id,
                    'line_id' => $temp_redo_data->line_id,
                    'section_id' => $temp_redo_data->section_id,
                    'allocated_qty' => $temp_redo_data->allocated_qty,
                    'start_date' => $temp_redo_data->start_date,
                    'end_date' => $temp_redo_data->end_date,
                    'required_seconds' => $temp_redo_data->required_seconds,
                    'text' => $temp_redo_data->text,
                    'plan_text' => $temp_redo_data->plan_text,
                    'progress' => $temp_redo_data->progress,
                    'is_locked' => $temp_redo_data->is_locked,
                    'board_color' => $temp_redo_data->board_color,
                    'notes' => $temp_redo_data->notes,
                    'factory_id' => $temp_redo_data->factory_id,
                    'created_by' => $temp_redo_data->created_by,
                    'updated_by' => $temp_redo_data->updated_by,
                ];

                DB::table('undo_redo_sewing_plans')->where('id', $temp_redo_data->id)->update($undo_redo_table_data);
                DB::table('sewing_plans')->where('id', $temp_current_plan_data->id)->update($new_sewing_plan_data);
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Last plan data updated!'
                ]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong!'
                ]);
            }
        } else {
            abort('404');
        }
    }
}
