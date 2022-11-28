<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use PDF;
use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\BasicFinance\Exports\Export;
use SkylarkSoft\GoRMG\BasicFinance\PackageConst;
use SkylarkSoft\GoRMG\BasicFinance\DTO\BudgetDTO;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Services\Budgets\AcBudgetMonthWiseExpenseReport;

class AcBudgetMonthWiseExpenseReportController extends Controller
{
    public function index()
    {
        $accounts = Account::query()->factoryFilter()
            ->whereIn('type_id', [Account::EXPENSE_OP, Account::EXPENSE_NOP])
            ->pluck('name', 'id');

        return view(PackageConst::VIEW_NAMESPACE . '::reports.budget.month_wise_expense_report', [
            'accounts' => $accounts,
        ]);
    }

    public function reportData(Request $request)
    {
        $reportData = (new AcBudgetMonthWiseExpenseReport())->reportData($request, new BudgetDTO());

        return view(PackageConst::VIEW_NAMESPACE . '::reports.budget.month_wise_report_table', [
            'report_data' => $reportData
        ]);
    }

    public function pdf(Request $request)
    {
        $reportData = (new AcBudgetMonthWiseExpenseReport())->reportData($request, new BudgetDTO());

        $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadview(PackageConst::VIEW_NAMESPACE . '::reports.budget.pdf.month_wise_report_pdf', [
                'report_data' => $reportData
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('budget_month_wise_expense_report.pdf');
    }

    public function excel(Request $request)
    {
        $reportData = (new AcBudgetMonthWiseExpenseReport())->reportData($request, new BudgetDTO());
        $title = 'Budget Expense Report';
        $viewFile = PackageConst::PACKAGE_NAME . '::reports.budget.month_wise_report_table';

        return Excel::download(
            (new Export(['report_data' => $reportData], $title, $viewFile)),
            'month_wise_expense_report.xlsx'
        );
    }
}
