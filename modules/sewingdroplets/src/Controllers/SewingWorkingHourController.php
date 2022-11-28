<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Http\Request;
use DB, Session, Exception;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingWorkingHour;

class SewingWorkingHourController extends Controller
{
    public function getWorkingHoursDateForm($wh_year, $wh_month)
    {
        $sewing_working_hours = SewingWorkingHour::where(['year' => $wh_year, 'month' => $wh_month])->get();
        if (!$sewing_working_hours->count()) {
            $months = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ];
            $month = $months[$wh_month];
            $from = Carbon::parse('first day of ' . $month . ' ' . $wh_year);

            $to = Carbon::parse('last day of ' . $month . ' ' . $wh_year);

            $sewing_working_hours = collect($this->generateDateRange($from, $to));
        }

        $html = view('sewingdroplets::forms.get_working_hours_date_form', [
            'sewing_working_hours' => $sewing_working_hours
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {

            $dates[]['working_date'] = $date->format('Y-m-d');

        }

        return $dates;
    }

    public function sewingSectionWorkingHourUpdate(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'year' => 'required',
            'month' => 'required',
            'working_hours.*' => 'required|integer|min:1|max:23',
        ], [
            'year.required' => 'Year is required',
            'month.required' => 'Month is required',
            'working_hours.*.required' => 'Working hour is required',
            'working_hours.*.integer' => 'Working hour must be an integer',
            'working_hours.*.min' => 'Working hour must be must be at least 1',
            'working_hours.*.max' => 'Working hour must be must be at most 23',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        if (!$request->has('working_hours')) {
            return response()->json([
                'status' => 'error',
                'errors' => ['default' => [
                    'month' => [
                        0 => 'Working Hours not Given'
                    ]
                ]]
            ]);
        }
        try {
            DB::beginTransaction();
            $year = $request->year;
            $month = $request->month;
            if ($request->working_hours && count($request->working_hours)) {
                SewingWorkingHour::where(['year' => $year, 'month' => $month])->forceDelete();
                foreach ($request->working_hours as $key => $working_hour) {
                    $sewing_working_hour = new SewingWorkingHour();
                    $sewing_working_hour->year = $year;
                    $sewing_working_hour->month = $month;
                    $sewing_working_hour->day = date('d', strtotime($request->working_dates[$key]));
                    $sewing_working_hour->working_date = $request->working_dates[$key];
                    $sewing_working_hour->working_hour = $working_hour;
                    $sewing_working_hour->save();
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
                'errors' => null,
                'message' => $html
            ]);
        }

    }
}