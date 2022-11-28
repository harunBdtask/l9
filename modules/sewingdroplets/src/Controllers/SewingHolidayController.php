<?php

namespace SkylarkSoft\GoRMG\Sewingdroplets\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB, Session, Exception;
use SkylarkSoft\GoRMG\Sewingdroplets\Models\SewingHoliday;
use SkylarkSoft\GoRMG\Sewingdroplets\Rules\SewingHolidayOnExistingPlanRule;
use SkylarkSoft\GoRMG\Sewingdroplets\Rules\UniqueSewingHolidayRule;

class SewingHolidayController extends Controller
{
    public function index()
    {
        $sewing_holidays = SewingHoliday::orderBy('holiday', 'desc')->paginate();
        $html = view('sewingdroplets::pages.sewing_holidays', ['sewing_holidays' => $sewing_holidays])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function create()
    {
        $html = view('sewingdroplets::forms.sewing_holiday_multiple_create')->render();
        return response()->json([
            'html' => $html
        ]);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'holiday.*' => ['required', 'date', 'distinct', new UniqueSewingHolidayRule(), new SewingHolidayOnExistingPlanRule()],
        ], [
            'holiday.*.required' => 'Holiday is required',
            'holiday.*.date' => 'Holiday must be a date',
            'holiday.*.distinct' => 'Holiday has a duplicate value.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        if (!$request->has('holiday')) {
            return response()->json([
                'status' => 'error',
                'errors' => ['default' => [
                    'holiday' => [
                        0 => 'Holiday is required'
                    ]
                ]]
            ]);
        }
        try {
            DB::beginTransaction();
            $holidays = $request->holiday;
            if (count($holidays)) {
                foreach ($holidays as $holiday) {
                    $sewing_holiday = new SewingHoliday();
                    $sewing_holiday->holiday = $holiday;
                    $sewing_holiday->save();
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

    public function edit($id)
    {
        $sewing_holiday = SewingHoliday::findOrFail($id);
        $html = view('sewingdroplets::forms.sewing_holiday_edit', [
            'sewing_holiday' => $sewing_holiday
        ])->render();
        return response()->json([
            'html' => $html,
            'sewing_holiday' => $sewing_holiday
        ]);
    }

    public function update($id, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'holiday' => ['required', 'date', 'distinct', new UniqueSewingHolidayRule(), new SewingHolidayOnExistingPlanRule()],
        ], [
            'holiday.required' => 'Holiday is required',
            'holiday.date' => 'Holiday must be a date',
            'holiday.distinct' => 'Holiday has a duplicate value.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }
        try {
            DB::beginTransaction();
            $sewing_holiday = SewingHoliday::findOrFail($id);
            $sewing_holiday->holiday = $request->holiday;
            $sewing_holiday->save();
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

    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            $sewing_holiday = SewingHoliday::findOrFail($request->id);
            $sewing_holiday->delete();
            DB::commit();
            $html = view('partials.flash_message', [
                'message_class' => "success",
                'message' => "Data Deleted successfully!!"
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

    public function search(Request $request)
    {
        $q = $request->q;
        $sewing_holidays = SewingHoliday::orWhere('holiday', 'like', '%'. $q .'%')->orderBy('holiday', 'desc')->paginate();
        $html = view('sewingdroplets::pages.sewing_holidays', [
            'sewing_holidays' => $sewing_holidays,
            'q' => $q,
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    public function getSewingHolidays()
    {
        $sewing_holiday_query = SewingHoliday::get();
        $sewing_holidays = [];
        foreach ($sewing_holiday_query as $sewing_holiday) {
            $sewing_holidays[] = [
                'start_date' => Carbon::parse($sewing_holiday->holiday)->startOfDay()->toDateTimeString(),
                'end_date' => Carbon::parse($sewing_holiday->holiday)->endOfDay()->toDateTimeString()
            ];
        }

        return response()->json([
            'sewing_holidays' => $sewing_holidays
        ]);
    }
}