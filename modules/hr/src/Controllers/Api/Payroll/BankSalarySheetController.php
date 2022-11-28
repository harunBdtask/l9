<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Payroll;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SkylarkSoft\GoRMG\HR\Models\HrEmployeeOfficialInfo;
use SkylarkSoft\GoRMG\HR\Models\HrMonthlyPaymentSummary;
use Throwable;

class BankSalarySheetController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = HrEmployeeOfficialInfo::query();

        $request->validate([
            'department_id' => 'nullable',
            'bank_id'       => 'required',
            'account_no'    => 'nullable'
        ]);

        if ($departmentId = $request->department_id) {
            $query->where('department_id', $departmentId);
        }

        if ($accountNo = $request->account_no) {
            $query->where('account_no', $accountNo);
        }

        if ($bankId = $request->bank_id) {
            $query->where('bank_id', $bankId);
        }

        list($year, $month) = explode('-', $request->month);

        try {

            $data = HrMonthlyPaymentSummary::with(
                'employeeOfficialInfo.departmentDetails',
                'employeeOfficialInfo.designationDetails',
                'employeeOfficialInfo.sectionDetails',
                'employee'
            )
                ->whereMonth('pay_month', $month)
                ->whereYear('pay_month', $year)
                ->where('userid', $query->pluck('unique_id'))
                ->get();

            $view = view('hr::payroll.bank-salary-sheet', ['salaries' => $data])->render();

            return response()->json(['report' => $view]);
        } catch (Exception $e) {
            return response()->json(['report' => "<h1>Something Went Wrong!</h1>"]);
        } catch (Throwable $e) {
            return response()->json(['report' => "<h1>Something Went Wrong!</h1>"]);
        }
    }
}
