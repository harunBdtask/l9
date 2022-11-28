<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\HR\Models\HrHoliday;
use Symfony\Component\HttpFoundation\Response;

class HolidayController extends Controller
{

    public function index()
    {
        $holidays = HrHoliday::whereYear('date', date('Y'))->orderBy('date')->paginate();

        return view('hr::holidays.index', ['holidays' => $holidays]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'dates_start' => 'required',
            'dates_end'   => 'required|date|after_or_equal:dates_start',
        ]);

        $dateRange = CarbonPeriod::create(
            Carbon::parse($request->input('dates_start')),
            Carbon::parse($request->input('dates_end'))
        );

        try {
            DB::beginTransaction();

            collect($dateRange)
                ->each(function ($date) use ($request) {
                    HrHoliday::create(['name' => $request->input('name'), 'date' => $date->format('Y-m-d')]);
                });

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('success', 'Data Created Failed');
        }

        Session::flash('success', 'Data Created Successfully');
        return redirect()->back();
    }


    public function destroy($id)
    {
        try {
            HrHoliday::destroy($id);
            Session::flash('success', 'Data Deleted Successfully');
            return redirect()->back();
        } catch (\Exception $exception) {
            Session::flash('success', 'Data Deleted Failed');
            return redirect('hr/holidays');
        }
    }
}
