<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Payroll;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrMonthlyPaymentSummary;

class PayslipController extends Controller
{
    public function index()
    {
        $data = [];
        return view('hr::payroll.pay_slip', $data);
    }

    public function generatePayslip(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
        ]);
        $year = $request->year ?? null;
        $month = $request->month ?? null;
        $department_id = $request->department_id ?? null;
        $section_id = $request->section_id ?? null;
        $unique_ids = $request->unique_id ?? null;
        $type = $request->type ?? null;
        $userids = HrEmployeeOfficialInfo::when($type != null, function($query) use($type) {
            return $query->where('type', $type);
        })->when($department_id != null, function ($query) use($department_id) {
            return $query->where('department_id', $department_id);
        })->when($section_id != null, function ($query) use($section_id) {
            return $query->where('section_id', $section_id);
        })->when($unique_ids != null,function ($query) use($unique_ids) {
            return $query->where('unique_id', $unique_ids);
        })->pluck('unique_id')->toArray();

        $pay_month = Carbon::parse($year . '-' . $month . '-01')->toDateString();

        $monthly_payment_summaries = HrMonthlyPaymentSummary::where('pay_month', $pay_month)
            ->when((is_array($userids) && count($userids) > 0), function ($query) use($userids) {
                return $query->whereIn('userid', $userids);
            })
            ->when((is_array($unique_ids) && count($unique_ids) > 0), function ($query) use($unique_ids) {
                return $query->whereIn('userid', $unique_ids);
            })->get();

        $view = view('hr::payroll.pay_slip', [
            'monthly_payment_summaries' => $monthly_payment_summaries
        ])->render();

        return response()->json([
            'view' => $view
        ]);
    }
}
