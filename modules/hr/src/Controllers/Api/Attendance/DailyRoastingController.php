<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Attendance;


use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\HR\Controllers\Api\RESTApiBaseController;
use SkylarkSoft\GoRMG\HR\Models\HrDailyRoasting;
use SkylarkSoft\GoRMG\HR\Services\Constants\CrudMessages;

class DailyRoastingController extends RESTApiBaseController
{
    public function index()
    {
        $roastings = HrDailyRoasting::with('employee', 'shift')->latest('date')->get();
        return $this->jsonSuccess(['data' => $roastings]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'form.*.employee_id' => 'required',
            'form.*.shift_id'    => 'required',
            'form.*.off_day'     => 'required',
            'dates.start'        => 'required',
            'dates.end'          => 'required'
        ], [
            'dates.start.required'        => 'Start Date is required!',
            'dates.end.required'          => 'End Date is required!',
            'form.*.shift_id.required'    => 'Shift is required!',
            'form.*.employee_id.required' => 'Unique Id is required!',
            'form.*.off_day.required' => 'Unique Id is required!',
        ]);


        try {

            DB::beginTransaction();

            foreach (request('form') as $emp) {
                CarbonPeriod::create(
                    Carbon::parse(request('dates.start')),
                    Carbon::parse(request('dates.end'))
                )->forEach(function ($date) use ($emp) {

                    $roasting = HrDailyRoasting::firstOrNew([
                        'employee_id' => $emp['employee_id'],
                        'date' => $date->toDateString()
                    ]);

                    $roasting->shift_id = $emp['shift_id'];

                    if ($date->format('l') === $emp['off_day']) {
                        $roasting->off_day_status = HrDailyRoasting::OFF_DAY;
                    }

                    $roasting->save();
                });
            }

            DB::commit();
            return $this->jsonSuccess(['data' => ''], CrudMessages::SAVE_SUCCESS);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => CrudMessages::SAVE_ERROR . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            HrDailyRoasting::destroy($id);
            return response()->json(['success' => true, 'message' => CrudMessages::DELETE_ERROR]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => CrudMessages::SWW]);
        }
    }
}
