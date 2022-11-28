<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\Reports;

use PDF;
use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use SkylarkSoft\GoRMG\Inventory\Filters\Filter;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssue;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\DailyYarnIssueStatementService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Exports\DailyYarnIssueReport;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;

class YarnIssueDailyReportController extends Controller
{
    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function index(Request $request, $type = null)
    {
        $yarnIssueQuery = YarnIssue::query();
        $data['issueList'] = $yarnIssueQuery->pluck('issue_no');
        $data['challanList'] = $yarnIssueQuery->pluck('challan_no');
        $data['lotList'] = YarnIssueDetail::query()->pluck('yarn_lot');
        $data['factories'] = Factory::query()->get(['id', 'factory_name as text']);
        $data['loanPartyList'] = Supplier::query()
            ->where('party_type', Supplier::LOAN_PARTY)
            ->get(['id', 'name as text']);

        $reportData = (new DailyYarnIssueStatementService())->getData($request);
        if ($type === 'pdf') {
            return PDF::loadView('inventory::yarns.reports.yarn-issue.yarn-issue-daily-report-pdf', compact('reportData', 'data'))
                ->setPaper('a4', 'landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ])->stream('yarn_issue_daily_report.pdf');
        }
        if ($type === 'excel') {
            return Excel::download(new DailyYarnIssueReport($reportData), 'daily_yarn_issue_statement.xlsx');
        }

        return view('inventory::yarns.reports.yarn-issue.yarn-issue-daily-report', compact('reportData', 'data'));
    }
}
