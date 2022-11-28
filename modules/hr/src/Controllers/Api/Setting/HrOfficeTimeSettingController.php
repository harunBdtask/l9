<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\HR\Models\HrOfficeTimeSetting;
use Illuminate\Http\Request;
use Exception;

class HrOfficeTimeSettingController extends Controller
{
    public function index()
    {
        $officeTimeSetting = HrOfficeTimeSetting::find(1);

        return view('hr::officetime-settings.index', ['officeTimeSetting' => $officeTimeSetting]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'worker_office_time' => 'required',
            'worker_late_allowed_minute' => 'required',
            'staff_office_time' => 'required',
            'staff_late_allowed_minute' => 'required',
            'management_office_time' => 'required',
            'management_late_allowed_minute' => 'required',
        ]);

        $hrOfficeTimeSetting = HrOfficeTimeSetting::query();

        try {
            $hrOfficeTimeSetting = $hrOfficeTimeSetting->find(1);
            $hrOfficeTimeSetting->fill($request->all())->save();

            Session::flash('success', "Successfully Updated");
            return redirect('hr/office-time-settings');
        } catch (Exception $e) {
            Session::flash('error', 'Something Went Wrong');

            return redirect('hr/office-time-settings');
        }

    }

}
