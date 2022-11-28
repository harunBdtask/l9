<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use PDF;
use Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use SkylarkSoft\GoRMG\BasicFinance\PackageConst;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\Journal;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use SkylarkSoft\GoRMG\BasicFinance\Models\Voucher;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\BasicFinance\Models\CostCenter;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department;
use SkylarkSoft\GoRMG\BasicFinance\Models\VoucherComment;
use SkylarkSoft\GoRMG\BasicFinance\Services\CurrencyService;
use SkylarkSoft\GoRMG\BasicFinance\Exports\LedgerReportExcel;
use SkylarkSoft\GoRMG\BasicFinance\Exports\LedgerV3ReportExcel;
use SkylarkSoft\GoRMG\BasicFinance\Services\AccountGroupService;
use SkylarkSoft\GoRMG\BasicFinance\Exports\GroupLedgerReportExcelNew;
use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\ReportViewService;
use SkylarkSoft\GoRMG\BasicFinance\Exports\ProvisionalLedgerReportExcel;
use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\LedgerReportService;
use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\BankManagementService;
use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\CashManagementService;
use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\GroupLedgerReportService;
use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\ProvisionalLedgerReportService;
use SkylarkSoft\GoRMG\BasicFinance\Controllers\API\TrialBalanceAccountsAPIController;
use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\TrialBalance\TrialBalanceReportStrategy;

//use SkylarkSoft\GoRMG\BasicFinance\Services\AccountGroupService;
//use SkylarkSoft\GoRMG\BasicFinance\Services\Reports\TrialBalance\TrialBalanceReportStrategy;

class FinancialReportsController extends Controller
{
    private $startDate;
    private $endDate;

    public function __construct(Request $request)
    {
        if ($request->has('start_date')) {
            $this->startDate = Carbon::parse($request->get('start_date'));
        } else {
            $this->startDate = Carbon::today()->startOfMonth();
        }

        if ($request->has('end_date')) {
            $this->endDate = Carbon::parse($request->get('end_date'));
        } else {
            $this->endDate = Carbon::today()->endOfMonth();
        }

        $this->endDate->addDay();
    }

    public function ledgerReport(Request $request)
    {
        $companyId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? '';
        $unitId = $request->get('unit_id') ?? '';
        $departmentId = $request->get('department_id') ?? '';
        $costCenterId = $request->get('cost_centre') ?? '';
        $currencyTypeId = $request->get('currency_type_id') ?? 0;
        $companies = Factory::all();
        $projects = [];
        $units = [];
        $departments = Department::query()->pluck('department', 'id')->all();
        $costCenters = CostCenter::query()->pluck('cost_center', 'id')->all();

        $accounts = Account::query()
            ->where('factory_id', $companyId)
            ->where('is_transactional', 1)
            ->where('is_active', 1)
            ->with([
                'journalEntries' => function ($query) use ($companyId, $projectId, $unitId, $departmentId, $costCenterId) {
                    return $query->whereDate('trn_date', '>=', $this->startDate)
                        ->whereDate('trn_date', '<', $this->endDate)
                        ->when($companyId, function ($query) use ($companyId) {
                            $query->whereHas('account', function ($query) use ($companyId) {
                                $query->where('factory_id', $companyId);
                            });
                        })->when($projectId, function ($query) use ($projectId) {
                            $query->where('project_id', $projectId);
                        })->when($unitId, function ($query) use ($unitId) {
                            $query->where('unit_id', $unitId);
                        })->when($departmentId, function ($query) use ($departmentId) {
                            $query->where('department_id', $departmentId);
                        })->when($costCenterId, function ($query) use ($costCenterId) {
                            $query->where('cost_center_id', $costCenterId);
                        });
                }
            ])
            ->orderBy('id')
            ->get();
        $ledgerAccs = $accounts->pluck('name', 'id');
        $account = Account::query()->where('factory_id', factoryId())
            ->where('is_transactional', 1)
            ->where('is_active', 1)
            ->with([
                'journalEntries' => function ($query) use ($companyId, $projectId, $unitId, $departmentId, $costCenterId) {
                    return $query->whereDate('trn_date', '>=', $this->startDate)
                        ->whereDate('trn_date', '<', $this->endDate)
                        ->when($companyId, function ($query) use ($companyId) {
                            $query->whereHas('account', function ($query) use ($companyId) {
                                $query->where('factory_id', $companyId);
                            });
                        })->when($projectId, function ($query) use ($projectId) {
                            $query->where('project_id', $projectId);
                        })->when($unitId, function ($query) use ($unitId) {
                            $query->where('unit_id', $unitId);
                        })->when($departmentId, function ($query) use ($departmentId) {
                            $query->where('department_id', $departmentId);
                        })->when($costCenterId, function ($query) use ($costCenterId) {
                            $query->where('cost_center_id', $costCenterId);
                        });
                }
            ])->find(request('account_id'));
        $requestTranId = request('account_id');

        $account = $account ?? $accounts->first() ?? [];

        $voucherIds = [];
        if (isset($account->journalEntries) && !empty($account->journalEntries)) {
            $voucherIds = collect($account->journalEntries)->pluck('voucher_id')->unique()->toArray();
        }
        $voucher = Voucher::query()->where('factory_id', Auth::user()->factory_id)->whereIn('id', $voucherIds)->get();
        $account['reference_no'] = collect($voucher)->flatten(1)->pluck('reference_no');

        $openingBalance = $balance = $account->openingLedgerBalance($this->startDate ?? now(), $companyId, $projectId, $unitId, $departmentId, $costCenterId);
        $openingFcBalance = $fcBalance = $account->openingLedgerFcBalance($this->startDate ?? now(), $companyId, $projectId, $unitId, $departmentId, $costCenterId);

        $ledgerReportFormattedData['allItemsArray'] = [];
        $ledgerReportFormattedData['accountCodesArray'] = [];
        $ledgerReportFormattedData['accountHeadsArray'] = [];
        $ledgerReportFormattedData['accountParticularsArray'] = [];
        $ledgerReportFormattedData['debitBalancesArray'] = [];
        $ledgerReportFormattedData['creditBalancesArray'] = [];
        if (isset($account->journalEntries) && !empty($account->journalEntries)) {
            $ledgerReportFormattedData = LedgerReportService::ledgerFormatter($account, $requestTranId);
        }

        $header = ReportViewService::for('search_info')
            ->setFactoryId($companyId)
            ->setFromDate($this->startDate)
            ->setToDate($this->endDate->subDay())
            ->render();

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::print.ledger', [
                'factoryId' => $companyId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'units' => $units,
                'projects' => $projects,
                'companies' => $companies,
                'departments' => $departments,
                'cost_centers' => $costCenters,
                'openingBalance' => $openingBalance,
                'openingFcBalance' => $openingFcBalance,
                'balance' => $balance,
                'fcBalance' => $fcBalance,
                'accounts' => $ledgerAccs,
                'account' => $account,
                'requestTranId' => $requestTranId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate->subDay(),
                'header' => $header
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('ledger-report-' . date('d:m:Y') . '.pdf');
        }

        if ($request->get('type') == 'excel') {
            $viewData = [
                'factoryId' => $companyId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'units' => $units,
                'projects' => $projects,
                'companies' => $companies,
                'departments' => $departments,
                'cost_centers' => $costCenters,
                'openingBalance' => $openingBalance,
                'openingFcBalance' => $openingFcBalance,
                'balance' => $balance,
                'fcBalance' => $fcBalance,
                'accounts' => $ledgerAccs,
                'account' => $account,
                'requestTranId' => $requestTranId,
                'currencyTypeId' => $currencyTypeId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate->subDay(),
                'allItemsArray' => $ledgerReportFormattedData['allItemsArray'],
            ];
            return Excel::download(new LedgerReportExcel($viewData), 'ledger_report_' . date('d:m:Y') . '.xlsx');
        }

        return view(PackageConst::VIEW_NAMESPACE . '::reports.ledger', [
            'factoryId' => $companyId,
            'projectId' => $projectId,
            'unitId' => $unitId,
            'units' => $units,
            'projects' => $projects,
            'companies' => $companies,
            'departments' => $departments,
            'cost_centers' => $costCenters,
            'openingBalance' => $openingBalance,
            'openingFcBalance' => $openingFcBalance,
            'balance' => $balance,
            'fcBalance' => $fcBalance,
            'accounts' => $ledgerAccs,
            'account' => $account,
            'requestTranId' => $requestTranId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate->subDay(),
            'header' => $header
        ]);
    }

    public function voucherDetails(Request $request)
    {
        $voucherNo = $request->get('voucher_no');
        $account = $request->get('account');
        $voucher = Voucher::query()->where('factory_id', Auth::user()->factory_id)->where('voucher_no', $voucherNo)->first();
        return view('basic-finance::tables.voucher_details_table', [
            'voucher' => $voucher, 'account' => $account,
        ]);
    }

    public function ledgerReportV3(Request $request)
    {
        
        $companyId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? '';
        $unitId = $request->get('unit_id') ?? '';
        $departmentId = $request->get('department_id') ?? '';
        $costCenterId = $request->get('cost_centre') ?? '';
        $currencyTypeId = $request->get('currency_type_id') ?? 0;
        $companies = Factory::all();
        $projects = [];
        $units = [];
        $departments = Department::query()->pluck('department', 'id')->all();
        $costCenters = CostCenter::query()->pluck('cost_center', 'id')->all();

        $accounts = Account::query()
            ->where('factory_id', $companyId)
            ->where('is_transactional', 1)
            ->where('is_active', 1)
            ->with([
                'journalEntries' => function ($query) use ($companyId, $projectId, $unitId, $departmentId, $costCenterId) {
                    return $query->whereDate('trn_date', '>=', $this->startDate)
                        ->whereDate('trn_date', '<', $this->endDate)
                        ->when($companyId, function ($query) use ($companyId) {
                            $query->whereHas('account', function ($query) use ($companyId) {
                                $query->where('factory_id', $companyId);
                            });
                        })->when($projectId, function ($query) use ($projectId) {
                            $query->where('project_id', $projectId);
                        })->when($unitId, function ($query) use ($unitId) {
                            $query->where('unit_id', $unitId);
                        })->when($departmentId, function ($query) use ($departmentId) {
                            $query->where('department_id', $departmentId);
                        })->when($costCenterId, function ($query) use ($costCenterId) {
                            $query->where('cost_center_id', $costCenterId);
                        })
                         ->orderBy('trn_date', 'ASC')
                         ->orderBy('voucher_no', 'ASC');
                }
                
            ])
            ->get();
        // dd($accounts->toArray()); 
        // return response()->json([$accounts->toArray()]);
        //exit;
        $ledgerAccs = $accounts->pluck('name', 'id');
        $account = Account::query()->where('factory_id', factoryId())
            ->where('is_transactional', 1)
            ->where('is_active', 1)
            ->with([
                'journalEntries' => function ($query) use ($companyId, $projectId, $unitId, $departmentId, $costCenterId) {
                    return $query->whereDate('trn_date', '>=', $this->startDate)
                        ->whereDate('trn_date', '<', $this->endDate)
                        ->when($companyId, function ($query) use ($companyId) {
                            $query->whereHas('account', function ($query) use ($companyId) {
                                $query->where('factory_id', $companyId);
                            });
                        })->when($projectId, function ($query) use ($projectId) {
                            $query->where('project_id', $projectId);
                        })->when($unitId, function ($query) use ($unitId) {
                            $query->where('unit_id', $unitId);
                        })->when($departmentId, function ($query) use ($departmentId) {
                            $query->where('department_id', $departmentId);
                        })->when($costCenterId, function ($query) use ($costCenterId) {
                            $query->where('cost_center_id', $costCenterId);
                        });
                }
            ])->find(request('account_id'));
        $requestTranId = request('account_id');

        $account = $account ?? $accounts->first() ?? [];

        $voucherIds = [];
        if (isset($account->journalEntries) && !empty($account->journalEntries)) {
            $voucherIds = collect($account->journalEntries)->pluck('voucher_id')->unique()->toArray();
        }
        $voucher = Voucher::query()->where('factory_id', Auth::user()->factory_id)->whereIn('id', $voucherIds)->get();

        $account['reference_no'] = collect($voucher)->flatten(1)->pluck('reference_no');

        $openingBalance = $balance = $account->openingLedgerBalance($this->startDate ?? now(), $companyId, $projectId, $unitId, $departmentId, $costCenterId);
        $openingFcBalance = $fcBalance = $account->openingLedgerFcBalance($this->startDate ?? now(), $companyId, $projectId, $unitId, $departmentId, $costCenterId);


        $header = ReportViewService::for('search_info')
            ->setFactoryId($companyId)
            ->setFromDate($this->startDate)
            ->setToDate($this->endDate->subDay())
            ->render();

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::print.ledger_v3', [
                'factoryId' => $companyId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'units' => $units,
                'projects' => $projects,
                'companies' => $companies,
                'departments' => $departments,
                'cost_centers' => $costCenters,
                'openingBalance' => $openingBalance,
                'openingFcBalance' => $openingFcBalance,
                'balance' => $balance,
                'fcBalance' => $fcBalance,
                'accounts' => $ledgerAccs,
                'account' => $account,
                'requestTranId' => $requestTranId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate->subDay(),
                'header' => $header
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('ledger-report-' . date('d:m:Y') . '.pdf');
        }

        if ($request->get('type') == 'excel') {
            $viewData = [
                'factoryId' => $companyId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'units' => $units,
                'projects' => $projects,
                'companies' => $companies,
                'departments' => $departments,
                'cost_centers' => $costCenters,
                'openingBalance' => $openingBalance,
                'openingFcBalance' => $openingFcBalance,
                'balance' => $balance,
                'fcBalance' => $fcBalance,
                'accounts' => $ledgerAccs,
                'account' => $account,
                'requestTranId' => $requestTranId,
                'currencyTypeId' => $currencyTypeId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate->subDay(),
            ];
            return Excel::download(new LedgerV3ReportExcel($viewData), 'ledger_report_' . date('d:m:Y') . '.xlsx');
        }


        return view(PackageConst::VIEW_NAMESPACE . '::reports.ledger_v3', [
            'factoryId' => $companyId,
            'projectId' => $projectId,
            'unitId' => $unitId,
            'units' => $units,
            'projects' => $projects,
            'companies' => $companies,
            'departments' => $departments,
            'cost_centers' => $costCenters,
            'openingBalance' => $openingBalance,
            'openingFcBalance' => $openingFcBalance,
            'balance' => $balance,
            'fcBalance' => $fcBalance,
            'accounts' => $ledgerAccs,
            'account' => $account,
            'requestTranId' => $requestTranId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate->subDay(),
            'header' => $header
        ]);
    }

    public function ledgerReportV2(Request $request)
    {
        $companyId = $request->get('company_id') ?? '';
        $projectId = $request->get('project_id') ?? '';
        $unitId = $request->get('unit_id') ?? '';
        $departmentId = $request->get('department_id') ?? '';
        $costCenterId = $request->get('cost_centre') ?? '';
        $companies = Factory::query()->pluck('factory_name as name', 'id')->all();
        $projects = [];
        $units = [];
        $departments = Department::query()->pluck('department', 'id')->all();
        $costCenters = CostCenter::query()->pluck('cost_center', 'id')->all();
        $accounts = Account::query()->with('journalEntries')->orderBy('id');
        $ledgerAccount = $accounts->get()->filter(function ($account) {
            return $account->children()->isEmpty();
        })->pluck('name', 'id');

        if ($companyId) {
            $projects = Project::query()->where('factory_id', $companyId)->get()->pluck('project', 'id');
        }
        if ($projectId) {
            $units = Unit::query()->where('factory_id', $companyId)
                ->where('bf_project_id', $projectId)
                ->get()->pluck('unit', 'id');
        }

        $account = Account::with([
            'journalEntries' => function ($query) use ($companyId, $projectId, $unitId, $departmentId, $costCenterId) {
                return $query->whereBetween('trn_date', [$this->startDate, $this->endDate])
                    ->when($companyId, function ($query) use ($companyId) {
                        $query->whereHas('account', function ($query) use ($companyId) {
                            $query->where('factory_id', $companyId);
                        });
                    })->when($projectId, function ($query) use ($projectId) {
                        $query->where('project_id', $projectId);
                    })->when($unitId, function ($query) use ($unitId) {
                        $query->where('unit_id', $unitId);
                    })->when($departmentId, function ($query) use ($departmentId) {
                        $query->where('department_id', $departmentId);
                    })->when($costCenterId, function ($query) use ($costCenterId) {
                        $query->where('cost_center_id', $costCenterId);
                    })
                    ->orderBy('trn_date');
            }
        ])->find($request->get('account_id'));
        return view('basic-finance::reports.ledger_v2', [
            'units' => $units,
            'projects' => $projects,
            'companies' => $companies,
            'account' => $account,
            'departments' => $departments,
            'cost_centers' => $costCenters,
            'ledger_accounts' => $ledgerAccount,
        ]);
    }

    public function trialBalance(Request $request)
    {
        $accounts = Journal::with('account')
            ->get()
            ->groupBy('account_id')
            ->map(function ($entries) {
                $account = $entries->first()->account;
                $account->openingBalance = $account->transactionBalance = $account->closingBalance = 0.00;
                foreach ($entries as $entry) {
                    if (Carbon::parse($entry->trn_date)->format('m-d-Y') < Carbon::parse($this->startDate)->format('m-d-Y')) {
                        $account->openingBalance = ($entry->trn_type === 'cr') ? $account->openingBalance + (-1) * $entry->trn_amount : $account->openingBalance + $entry->trn_amount;
                    }
                    if ((Carbon::parse($entry->trn_date)->format('m-d-Y') >= Carbon::parse($this->startDate)->format('m-d-Y')) &&
                        (Carbon::parse($entry->trn_date)->format('m-d-Y') < Carbon::parse($this->endDate)->format('m-d-Y'))
                    ) {
                        $account->transactionBalance = ($entry->trn_type === 'cr') ? $account->transactionBalance + (-1) * $entry->trn_amount : $account->transactionBalance + $entry->trn_amount;
                    }
                    if (Carbon::parse($entry->trn_date)->format('m-d-Y') < Carbon::parse($this->endDate)->format('m-d-Y')) {
                        $account->closingBalance = ($entry->trn_type === 'cr') ? $account->closingBalance  + (-1) * $entry->trn_amount : $account->closingBalance  + $entry->trn_amount;
                    }
                }
                return $account;
            });
        if ($request->print == true) {
            return view('basic-finance::print.trial_balance', [
                'accounts' => $accounts,
                'report_title' => 'Trial Balance',
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);
        }
        return view('basic-finance::reports.trial_balance', [
            'accounts' => $accounts,
        ]);
    }

    public
    function trialBalanceV2(Request $request)
    {
        $companyId = $request->get('company_id') ?? '';
        $projectId = $request->get('project_id') ?? '';
        $unitId = $request->get('unit_id') ?? '';
        $groupId = $request->get('group_id') ?? null;
        $companies = Factory::query()->pluck('factory_name as name', 'id')->all();
        $projects = [];
        $units = [];

        if ($companyId) {
            $projects = Project::query()->where('factory_id', $companyId)->get()->pluck('project', 'id');
        }
        if ($projectId) {
            $units = Unit::query()->where('factory_id', $companyId)
                ->where('bf_project_id', $projectId)
                ->get()->pluck('unit', 'id');
        }
        $groups = collect(AccountGroupService::groups())->pluck('text', 'id');
        $reportData = (new TrialBalanceReportStrategy)->setGroupId($groupId)
            ->setData($request)
            ->generateReport();

        return view('basic-finance::reports.trial_balance_v2', [
            'units' => $units,
            'projects' => $projects,
            'groups' => $groups,
            'unit_id' => $unitId,
            'companies' => $companies,
            'end_date' => $this->endDate,
            'report_data' => $reportData,
            'start_date' => $this->startDate,
        ]);
    }

    public function cashBook(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $fromDate = $request->get('from_date') ?? Carbon::today()->startOfMonth();
        $toDate = $request->get('to_date') ?? Carbon::today()->endOfMonth();
        $factories = Factory::all();
        $projects = $units = [];

        $cashBookAccounts = Account::query()
            ->where('code', 'LIKE', '1201001%')
            ->where('is_active', 1)
            ->where('is_transactional', 1)
            ->get();

        $account_codes = $cashBookAccounts->map(function ($data) {
            return $data->code;
        })->toArray();

        $journalUnitWiseData = Journal::query()->whereIn('account_code', $account_codes)
            ->with('factory', 'project', 'unit')
            ->when($factoryId, function ($query) use ($factoryId) {
                $query->whereHas('account', function ($query) use ($factoryId) {
                    $query->where('factory_id', $factoryId);
                });
            })->when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })->groupBy(['unit_id', 'project_id', 'factory_id', 'account_code'])
            ->select('account_id','unit_id', 'project_id', 'factory_id', 'account_code')
            ->orderBy('account_code')
            ->get()->map(function ($item) use ($fromDate, $toDate) {
                $unit_wise_total_opening_balance = (Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'dr')
                    ->where('trn_date', '<', $fromDate)
                    ->orderBy('id')
                    ->sum('trn_amount')
                ) - (Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'cr')
                    ->where('trn_date', '<', $fromDate)
                    ->orderBy('id')
                    ->sum('trn_amount'));

                $unit_wise_total_debit_balance = Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'dr')
                    ->where('trn_date', '>=', $fromDate)
                    ->where('trn_date', '<=', $toDate)
                    ->orderBy('id')
                    ->sum('trn_amount');

                $unit_wise_total_credit_balance = Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'cr')
                    ->where('trn_date', '>=', $fromDate)
                    ->where('trn_date', '<=', $toDate)
                    ->orderBy('id')
                    ->sum('trn_amount');

                $unit_wise_total_closing_balance = (Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'dr')
                    ->where('trn_date', '<=', $toDate)
                    ->orderBy('id')
                    ->sum('trn_amount')
                ) - (Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'cr')
                    ->where('trn_date', '<=', $toDate)
                    ->orderBy('id')
                    ->sum('trn_amount'));


                return [
                    'account_id' => $item->account_id,
                    'account_code' => $item->account_code,
                    'factory_id' => $item->factory_id,
                    'project_id' => $item->project_id,
                    'unit_id' => $item->unit_id,
                    'account_name' => collect(Account::query()->where('code', $item->account_code)->select('name')->get())->first()->name,
                    'factory_name' => $item->factory->factory_name,
                    'project_name' => $item->project->project,
                    'unit_name' => $item->unit->unit,
                    'unit_wise_total_opening_balance' => $unit_wise_total_opening_balance,
                    'unit_wise_total_closing_balance' => $unit_wise_total_closing_balance,
                    'unit_wise_total_debit_balance' => $unit_wise_total_debit_balance,
                    'unit_wise_total_credit_balance' => $unit_wise_total_credit_balance,
                ];
            });

        if ($request->get('type') === 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::.reports.cash-management.cash-book.pdf', compact(
                'factories',
                'projects',
                'units',
                'factoryId',
                'projectId',
                'unitId',
                'fromDate',
                'toDate',
                'journalUnitWiseData'
            ))->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('cash-book-report.pdf');
        }

        return view('basic-finance::reports.cash-management.cash-book.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId',
            'fromDate',
            'toDate',
            'journalUnitWiseData'
        ));
    }

    public function detailedCashBook(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $costCenterId = $request->get('cost_center') ?? null;
        $ledgerId = $request->get('ledger') ?? null;
        $balanceId = $request->get('balance') ?? null;
        $fromDate = $request->get('from_date') ?? Carbon::today()->startOfMonth();
        $toDate = $request->get('to_date') ?? Carbon::today()->endOfMonth();
        $factories = Factory::all();
        $costCenters = CostCenter::all();
        $balances = (object) array(
            [
                'id' => Journal::DEBIT,
                'text' => 'Debit'
            ],
            [
                'id' => Journal::CREDIT,
                'text' => 'Credit'
            ]
        );
        $projects = $units = [];


        $cashBookAccounts = Account::query()
            ->where('code', 'LIKE', '1201001%')
            ->where('is_active', 1)
            ->where('is_transactional', 1)
            ->get();

        $account_codes = $cashBookAccounts->map(function ($data) {
            return $data->code;
        })->toArray();

        $journalUnitWiseData = Journal::query()->whereIn('account_code', $account_codes)
            ->with('account','voucher.cheque.chequeBook','factory', 'project', 'unit')
            ->when(($fromDate && $toDate), function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('trn_date', [$fromDate, $toDate]);
            })->when($factoryId, function ($query) use ($factoryId) {
                $query->whereHas('account', function ($query) use ($factoryId) {
                    $query->where('factory_id', $factoryId);
                });
            })->when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })->when($costCenterId, function ($query) use ($costCenterId) {
                $query->where('cost_center_id', $costCenterId);
            })->when($ledgerId, function ($query) use ($ledgerId) {
                $query->where('account_id', $ledgerId);
            })->when($balanceId, function ($query) use ($balanceId) {
                $query->where('trn_type', $balanceId);
            })
            ->orderBy('trn_date','ASC')
            ->orderBy('voucher_no', 'ASC')
            ->get();

        $opening_balance = CashManagementService::opening_balance([
                'account_codes' => $account_codes,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'factoryId' => $factoryId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'costCenterId' => $costCenterId,
                'ledgerId' => $ledgerId,
                'balanceId' => $balanceId
            ]);

        $closing_balance = CashManagementService::closing_balance([
                'account_codes' => $account_codes,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'factoryId' => $factoryId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'costCenterId' => $costCenterId,
                'ledgerId' => $ledgerId,
                'balanceId' => $balanceId
            ]);

        if ($request->get('type') === 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::.reports.cash-management.detailed-cash-book.pdf', compact(
                'factories',
                'projects',
                'units',
                'factoryId',
                'projectId',
                'unitId',
                'fromDate',
                'toDate',
                'journalUnitWiseData',
                'costCenterId',
                'costCenters',
                'ledgerId',
                'cashBookAccounts',
                'balanceId',
                'balances',
                'opening_balance',
                'closing_balance',
            ))->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('cash-book-report.pdf');
        }

        return view('basic-finance::reports.cash-management.detailed-cash-book.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId',
            'fromDate',
            'toDate',
            'journalUnitWiseData',
            'costCenterId',
            'costCenters',
            'ledgerId',
            'cashBookAccounts',
            'balanceId',
            'balances',
            'opening_balance',
            'closing_balance',
        ));
    }

    public
    function bankBook(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $fromDate = $request->get('from_date') ?? Carbon::today()->startOfMonth();
        $toDate = $request->get('to_date') ?? Carbon::today()->endOfMonth();
        $factories = Factory::all();
        $projects = $units = [];

        $bankBookAccounts = Account::query()
            ->where('code', 'LIKE', '1201002%')
            ->where('is_active', 1)
            ->where('is_transactional', 1)
            ->get();

        $account_codes = $bankBookAccounts->map(function ($data) {
            return $data->code;
        })->toArray();

        $journalUnitWiseData = Journal::query()->whereIn('account_code', $account_codes)
            ->with('factory', 'project', 'unit')
            ->when($factoryId, function ($query) use ($factoryId) {
                $query->whereHas('account', function ($query) use ($factoryId) {
                    $query->where('factory_id', $factoryId);
                });
            })->when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })->groupBy(['unit_id', 'project_id', 'factory_id', 'account_code'])
            ->select('account_id','unit_id', 'project_id', 'factory_id', 'account_code')
            ->orderBy('account_code')
            ->get()->map(function ($item) use ($fromDate, $toDate) {
                $unit_wise_total_opening_balance = (Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'dr')
                    ->where('trn_date', '<', $fromDate)
                    ->orderBy('id')
                    ->sum('trn_amount')
                ) - (Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'cr')
                    ->where('trn_date', '<', $fromDate)
                    ->orderBy('id')
                    ->sum('trn_amount'));

                $unit_wise_total_debit_balance = Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'dr')
                    ->where('trn_date', '>=', $fromDate)
                    ->where('trn_date', '<=', $toDate)
                    ->orderBy('id')
                    ->sum('trn_amount');

                $unit_wise_total_credit_balance = Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'cr')
                    ->where('trn_date', '>=', $fromDate)
                    ->where('trn_date', '<=', $toDate)
                    ->orderBy('id')
                    ->sum('trn_amount');

                $unit_wise_total_closing_balance = (Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'dr')
                    ->where('trn_date', '<=', $toDate)
                    ->orderBy('id')
                    ->sum('trn_amount')
                ) - (Journal::query()->where('account_code', $item->account_code)
                    ->where('factory_id', $item->factory_id)
                    ->where('project_id', $item->project_id)
                    ->where('unit_id', $item->unit_id)
                    ->where('trn_type', 'cr')
                    ->where('trn_date', '<=', $toDate)
                    ->orderBy('id')
                    ->sum('trn_amount'));


                return [
                    'account_id' => $item->account_id,
                    'account_code' => $item->account_code,
                    'factory_id' => $item->factory_id,
                    'project_id' => $item->project_id,
                    'unit_id' => $item->unit_id,
                    'account_name' => collect(Account::query()->where('code', $item->account_code)->select('name')->get())->first()->name,
                    'factory_name' => $item->factory->factory_name,
                    'project_name' => $item->project->project,
                    'unit_name' => $item->unit->unit,
                    'unit_wise_total_opening_balance' => $unit_wise_total_opening_balance,
                    'unit_wise_total_closing_balance' => $unit_wise_total_closing_balance,
                    'unit_wise_total_debit_balance' => $unit_wise_total_debit_balance,
                    'unit_wise_total_credit_balance' => $unit_wise_total_credit_balance,
                ];
            });

        if ($request->get('type') === 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::.pages.bank-management.bank-book.pdf', compact(
                'factories',
                'projects',
                'units',
                'factoryId',
                'projectId',
                'unitId',
                'fromDate',
                'toDate',
                'journalUnitWiseData'
            ))->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('bank-book-report.pdf');
        }
        return view('basic-finance::pages.bank-management.bank-book.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId',
            'fromDate',
            'toDate',
            'journalUnitWiseData'
        ));
    }

    public function detailedBankBook(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $costCenterId = $request->get('cost_center') ?? null;
        $ledgerId = $request->get('ledger') ?? null;
        $balanceId = $request->get('balance') ?? null;
        $fromDate = $request->get('from_date') ?? Carbon::today()->startOfMonth();
        $toDate = $request->get('to_date') ?? Carbon::today()->endOfMonth();
        $factories = Factory::all();
        $costCenters = CostCenter::all();
        $balances = (object) array(
            [
                'id' => Journal::DEBIT,
                'text' => 'Debit'
            ],
            [
                'id' => Journal::CREDIT,
                'text' => 'Credit'
            ]
        );
        $projects = $units = [];

       $bankBookAccounts = Account::query()
            ->where('code', 'LIKE', '1201002%')
            ->where('is_active', 1)
            ->where('is_transactional', 1)
            ->get();

        $account_codes = $bankBookAccounts->map(function ($data) {
            return $data->code;
        })->toArray();


       $journalUnitWiseData = Journal::query()->whereIn('account_code', $account_codes)
            ->with('account','voucher','factory', 'project', 'unit')
            ->when(($fromDate && $toDate), function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('trn_date', [$fromDate, $toDate]);
            })->when($factoryId, function ($query) use ($factoryId) {
                $query->whereHas('account', function ($query) use ($factoryId) {
                    $query->where('factory_id', $factoryId);
                });
            })->when($projectId, function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })->when($costCenterId, function ($query) use ($costCenterId) {
                $query->where('cost_center_id', $costCenterId);
            })->when($ledgerId, function ($query) use ($ledgerId) {
                $query->where('account_id', $ledgerId);
            })->when($balanceId, function ($query) use ($balanceId) {
                $query->where('trn_type', $balanceId);
            })
            ->orderBy('trn_date','asc')
            ->get();

        $opening_balance = BankManagementService::opening_balance([
                'account_codes' => $account_codes,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'factoryId' => $factoryId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'costCenterId' => $costCenterId,
                'ledgerId' => $ledgerId,
                'balanceId' => $balanceId
            ]);

        $closing_balance = BankManagementService::closing_balance([
                'account_codes' => $account_codes,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'factoryId' => $factoryId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'costCenterId' => $costCenterId,
                'ledgerId' => $ledgerId,
                'balanceId' => $balanceId
            ]);

            if ($request->get('type') === 'pdf') {
                $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::.pages.bank-management.detailed-bank-book.pdf', compact(
                    'factories',
                    'projects',
                    'units',
                    'factoryId',
                    'projectId',
                    'unitId',
                    'fromDate',
                    'toDate',
                    'journalUnitWiseData',
                    'costCenterId',
                    'costCenters',
                    'ledgerId',
                    'bankBookAccounts',
                    'balanceId',
                    'balances',
                    'opening_balance',
                    'closing_balance',
                ))->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
                return $pdf->stream('bank-book-report.pdf');
            }

        return view('basic-finance::pages.bank-management.detailed-bank-book.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId',
            'fromDate',
            'toDate',
            'journalUnitWiseData',
            'costCenterId',
            'costCenters',
            'ledgerId',
            'bankBookAccounts',
            'balanceId',
            'balances',
            'opening_balance',
            'closing_balance',
        ));
    }

    function arrayGroupBy(array $array, $key)
    {
        if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
            trigger_error('arrayGroupBy(): The key should be a string, an integer, or a callback', E_USER_ERROR);
            return null;
        }
        $func = (!is_string($key) && is_callable($key) ? $key : null);
        $_key = $key;
        $grouped = [];
        foreach ($array as $value) {
            $key = null;
            if (is_callable($func)) {
                $key = call_user_func($func, $value);
            } elseif (is_object($value) && property_exists($value, $_key)) {
                $key = $value->{$_key};
            } elseif (isset($value[$_key])) {
                $key = $value[$_key];
            }
            if ($key === null) {
                continue;
            }
            $grouped[$key][] = $value;
        }
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('arrayGroupBy', $params);
            }
        }
        return $grouped;
    }

    public
    function receiptsAndPaymentsAll(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? '';
        $unitId = $request->get('unit_id') ?? '';
        $accountIds = $request->get('account_ids') ?
            collect($request->get('account_ids'))->map(function ($item) {
                return (int)($item);
            })->toArray()
            : null;
        $factories = Factory::all();
        $projects = $units = $account_ids = [];

        $reportData['start_date'] = $startDate = $fromDate = $this->startDate ?? Carbon::now()->startOfMonth();
        $endDate = $toDate = $this->endDate ?? Carbon::now();
        $reportData['end_date'] = $this->endDate->subDay() ?? Carbon::now();
        $ids = $accountIds ?? array('1201000000000');
        $accounts = Account::query()->where('factory_id', $factoryId)->with([
            'journalEntries' => function ($item) use ($startDate, $endDate, $factoryId, $projectId, $unitId) {
                return $item->whereDate('trn_date', '>=', $startDate)
                    ->whereDate('trn_date', '<', $endDate)
                    ->when($factoryId, function ($query) use ($factoryId) {
                        return $query->where('factory_id', $factoryId);
                    })
                    ->when($projectId, function ($query) use ($projectId) {
                        return $query->where('project_id', $projectId);
                    })
                    ->when($unitId, function ($query) use ($unitId) {
                        return $query->where('unit_id', $unitId);
                    });
            }
        ])->whereIn('code', $ids)->get();
        $reportData['balances'] = $accounts->map(function ($account) use ($startDate, $endDate) {
            $openingBalance = $account->openingBalance($startDate);
            $closingBalance = $account->closingBalance($endDate);
            return [
                "name" => $account->name,
                "opening_balance" => $openingBalance,
                "closing_balance" => $closingBalance,
            ];
        });

        $debitVouchers = $accounts->map(function ($account) {
            return collect($account->journalEntries)->filter(function ($data) {
                return $data['trn_type'] == "dr";
            });
        })->flatten()->pluck('voucher_id');

        $reportData['received'] = Voucher::query()->where('factory_id', $factoryId)->whereIn('id', $debitVouchers)->get()->flatMap(function ($voucher) {
            $voucherDetails = collect($voucher->details);
            $voucherItems = collect($voucherDetails['items'])->filter(function ($item) {
                return isset($item->credit) && $item->credit > 0;
            });

            if (count($voucherItems)) {
                return collect($voucherDetails['items'])->map(function ($item) {
                    return [
                        'name' => $item->credit > 0 ? $item->account_name : null,
                        'amount' => $item->credit,
                        'id' => $item->credit > 0 ? $item->account_id : null,
                    ];
                })->whereNotNull('id');
            } else {
                return [
                    [
                        'name' => $voucherDetails['credit_account_name'],
                        'amount' => $voucherDetails['total_credit'],
                        'id' => $voucherDetails['credit_account_id'],
                    ]
                ];
            }
        })->groupBy('id');

        $creditVouchers = $accounts->map(function ($account) {
            return collect($account->journalEntries)->filter(function ($data) {
                return $data['trn_type'] == "cr";
            });
        })->flatten()->pluck('voucher_id')->unique();

        $reportData['payments'] = Voucher::query()->where('factory_id', $factoryId)->whereIn('id', $creditVouchers)->get()->flatMap(function ($voucher) {
            $voucherDetails = collect($voucher->details);
            $voucherItems = collect($voucherDetails['items'])->filter(function ($item) {
                return isset($item->debit) && $item->debit > 0;
            });

            if (count($voucherItems)) {
                return collect($voucherItems)->map(function ($item) {
                    return [
                        //                        'name' => $voucherDetails['debit_account_name'] ?? ($item->debit > 0 ? $item->account_name : null),
                        'name' => $item->debit > 0 ? $item->account_name : null,
                        'amount' => $item->debit,
                        'id' => $item->debit > 0 ? $item->account_id : null,
                    ];
                })->whereNotNull('id');
            } else {
                return [
                    [
                        'name' => $voucherDetails['debit_account_name'],
                        'amount' => $voucherDetails['total_debit'],
                        'id' => $voucherDetails['debit_account_id'],
                    ]
                ];
            }
        })->groupBy('id');

        if ($request->get('type') == 'excel') {

            return Excel::download(new RPreportExcel($reportData), 'receipt_payment_statement_' . date('d:m:Y') . '.xlsx');
        }

        if ($request->get('type') == 'pdf') {

            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::reports.receipt-payment-and-report.pdf', $reportData)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('receipt-payment-report-' . date('d:m:Y') . '.pdf');
        }
        return view('basic-finance::reports.receipt-payment-and-report.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId',
            'accountIds',
            'account_ids',
            'fromDate',
            'toDate',
            'reportData'
        ));
    }

    public
    function receiptsAndPaymentsCash(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? '';
        $unitId = $request->get('unit_id') ?? '';
        $accountIds = $request->get('account_ids') ?
            collect($request->get('account_ids'))->map(function ($item) {
                return (int)($item);
            })->toArray()
            : null;
        $factories = Factory::all();
        $projects = $units = $account_ids = [];

        $reportData['start_date'] = $startDate = $fromDate = $this->startDate ?? Carbon::now()->startOfMonth();
        $endDate = $toDate = $this->endDate ?? Carbon::now();
        $reportData['end_date'] = $this->endDate->subDay() ?? Carbon::now();
        $ids = $accountIds ?? array('1201001000000');
        $accounts = Account::query()->where('factory_id', $factoryId)->with([
            'journalEntries' => function ($item) use ($startDate, $endDate, $factoryId, $projectId, $unitId) {
                return $item->whereDate('trn_date', '>=', $startDate)
                    ->whereDate('trn_date', '<', $endDate)
                    ->when($factoryId, function ($query) use ($factoryId) {
                        return $query->where('factory_id', $factoryId);
                    })
                    ->when($projectId, function ($query) use ($projectId) {
                        return $query->where('project_id', $projectId);
                    })
                    ->when($unitId, function ($query) use ($unitId) {
                        return $query->where('unit_id', $unitId);
                    });
            }
        ])->whereIn('code', $ids)->get();
        $reportData['balances'] = $accounts->map(function ($account) use ($startDate, $endDate) {
            $openingBalance = $account->openingBalance($startDate);
            $closingBalance = $account->closingBalance($endDate);
            return [
                "name" => $account->name,
                "opening_balance" => $openingBalance,
                "closing_balance" => $closingBalance,
            ];
        });

        $debitVouchers = $accounts->map(function ($account) {
            return collect($account->journalEntries)->filter(function ($data) {
                return $data['trn_type'] == "dr";
            });
        })->flatten()->pluck('voucher_id');

        $reportData['received'] = Voucher::query()->where('factory_id', $factoryId)->whereIn('id', $debitVouchers)->get()->flatMap(function ($voucher) {
            $voucherDetails = collect($voucher->details);
            $voucherItems = collect($voucherDetails['items'])->filter(function ($item) {
                return isset($item->credit) && $item->credit > 0;
            });

            if (count($voucherItems)) {
                return collect($voucherDetails['items'])->map(function ($item) {
                    return [
                        'name' => $item->credit > 0 ? $item->account_name : null,
                        'amount' => $item->credit,
                        'id' => $item->credit > 0 ? $item->account_id : null,
                    ];
                })->whereNotNull('id');
            } else {
                return [
                    [
                        'name' => $voucherDetails['credit_account_name'],
                        'amount' => $voucherDetails['total_credit'],
                        'id' => $voucherDetails['credit_account_id'],
                    ]
                ];
            }
        })->groupBy('id');

        $creditVouchers = $accounts->map(function ($account) {
            return collect($account->journalEntries)->filter(function ($data) {
                return $data['trn_type'] == "cr";
            });
        })->flatten()->pluck('voucher_id')->unique();

        $reportData['payments'] = Voucher::query()->where('factory_id', $factoryId)->whereIn('id', $creditVouchers)->get()->flatMap(function ($voucher) {
            $voucherDetails = collect($voucher->details);
            $voucherItems = collect($voucherDetails['items'])->filter(function ($item) {
                return isset($item->debit) && $item->debit > 0;
            });

            if (count($voucherItems)) {
                return collect($voucherItems)->map(function ($item) {
                    return [
                        //                        'name' => $voucherDetails['debit_account_name'] ?? ($item->debit > 0 ? $item->account_name : null),
                        'name' => $item->debit > 0 ? $item->account_name : null,
                        'amount' => $item->debit,
                        'id' => $item->debit > 0 ? $item->account_id : null,
                    ];
                })->whereNotNull('id');
            } else {
                return [
                    [
                        'name' => $voucherDetails['debit_account_name'],
                        'amount' => $voucherDetails['total_debit'],
                        'id' => $voucherDetails['debit_account_id'],
                    ]
                ];
            }
        })->groupBy('id');

        if ($request->get('type') == 'pdf') {

            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::reports.cash-management.receipt-payment-and-report.pdf', $reportData)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('receipt-payment-report-' . date('d:m:Y') . '.pdf');
        }

        return view('basic-finance::reports.cash-management.receipt-payment-and-report.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId',
            'account_ids',
            'accountIds',
            'fromDate',
            'toDate',
            'reportData'
        ));
    }

    public function receiptsAndPaymentsBank(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? '';
        $unitId = $request->get('unit_id') ?? '';
        $accountIds = $request->get('account_ids') ?
            collect($request->get('account_ids'))->map(function ($item) {
                return (int)($item);
            })->toArray()
            : null;
        $factories = Factory::all();
        $projects = $units = $account_ids = [];

        $reportData['start_date'] = $startDate = $fromDate = $this->startDate ?? Carbon::now()->startOfMonth();
        $endDate = $toDate = $this->endDate ?? Carbon::now();
        $reportData['end_date'] = $this->endDate->subDay() ?? Carbon::now();
        $ids = $accountIds ?? array('1201002000000');
        $accounts = Account::query()->where('factory_id', $factoryId)->with([
            'journalEntries' => function ($item) use ($startDate, $endDate, $factoryId, $projectId, $unitId) {
                return $item->whereDate('trn_date', '>=', $startDate)
                    ->whereDate('trn_date', '<', $endDate)
                    ->when($factoryId, function ($query) use ($factoryId) {
                        return $query->where('factory_id', $factoryId);
                    })
                    ->when($projectId, function ($query) use ($projectId) {
                        return $query->where('project_id', $projectId);
                    })
                    ->when($unitId, function ($query) use ($unitId) {
                        return $query->where('unit_id', $unitId);
                    });
            }
        ])->whereIn('code', $ids)->get();
        $reportData['balances'] = $accounts->map(function ($account) use ($startDate, $endDate) {
            $openingBalance = $account->openingBalance($startDate);
            $closingBalance = $account->closingBalance($endDate);
            return [
                "name" => $account->name,
                "opening_balance" => $openingBalance,
                "closing_balance" => $closingBalance,
            ];
        });

        $debitVouchers = $accounts->map(function ($account) {
            return collect($account->journalEntries)->filter(function ($data) {
                return $data['trn_type'] == "dr";
            });
        })->flatten()->pluck('voucher_id');

        $reportData['received'] = Voucher::query()->where('factory_id', $factoryId)->whereIn('id', $debitVouchers)->get()->flatMap(function ($voucher) {
            $voucherDetails = collect($voucher->details);
            $voucherItems = collect($voucherDetails['items'])->filter(function ($item) {
                return isset($item->credit) && $item->credit > 0;
            });

            if (count($voucherItems)) {
                return collect($voucherDetails['items'])->map(function ($item) {
                    return [
                        'name' => $item->credit > 0 ? $item->account_name : null,
                        'amount' => $item->credit,
                        'id' => $item->credit > 0 ? $item->account_id : null,
                    ];
                })->whereNotNull('id');
            } else {
                return [
                    [
                        'name' => $voucherDetails['credit_account_name'],
                        'amount' => $voucherDetails['total_credit'],
                        'id' => $voucherDetails['credit_account_id'],
                    ]
                ];
            }
        })->groupBy('id');

        $creditVouchers = $accounts->map(function ($account) {
            return collect($account->journalEntries)->filter(function ($data) {
                return $data['trn_type'] == "cr";
            });
        })->flatten()->pluck('voucher_id')->unique();

        $reportData['payments'] = Voucher::query()->where('factory_id', $factoryId)->whereIn('id', $creditVouchers)->get()->flatMap(function ($voucher) {
            $voucherDetails = collect($voucher->details);
            $voucherItems = collect($voucherDetails['items'])->filter(function ($item) {
                return isset($item->debit) && $item->debit > 0;
            });

            if (count($voucherItems)) {
                return collect($voucherItems)->map(function ($item) {
                    return [
                        //                        'name' => $voucherDetails['debit_account_name'] ?? ($item->debit > 0 ? $item->account_name : null),
                        'name' => $item->debit > 0 ? $item->account_name : null,
                        'amount' => $item->debit,
                        'id' => $item->debit > 0 ? $item->account_id : null,
                    ];
                })->whereNotNull('id');
            } else {
                return [
                    [
                        'name' => $voucherDetails['debit_account_name'],
                        'amount' => $voucherDetails['total_debit'],
                        'id' => $voucherDetails['debit_account_id'],
                    ]
                ];
            }
        })->groupBy('id');

        if ($request->get('type') == 'excel') {

            return Excel::download(new RPreportExcel($reportData), 'receipt_payment_statement_' . date('d:m:Y') . '.xlsx');
        }

        if ($request->get('type') == 'pdf') {

            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::pages.bank-management.receipt-payment-and-report.pdf', $reportData)
                ->setPaper('a4')->setOrientation('landscape')->setOptions([
                    'header-html' => view('skeleton::pdf.header'),
                    'footer-html' => view('skeleton::pdf.footer'),
                ]);
            return $pdf->stream('receipt-payment-report-' . date('d:m:Y') . '.pdf');
        }
        return view('basic-finance::pages.bank-management.receipt-payment-and-report.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId',
            'account_ids',
            'accountIds',
            'fromDate',
            'toDate',
            'reportData'
        ));
    }

    public
    function incomeStatement(Request $request)
    {
        // select only revenue and expense type accounts
        $accounts = Account::with([
            'journalEntries' => function ($query) {
                return $query->where('trn_date', '>=', $this->startDate)
                    ->where('trn_date', '<', $this->endDate);
            }
        ])->whereIn('type_id', [
            Account::INCOME,
            Account::EXPENSE
        ])
            ->get()
            ->filter(function ($account) {
                return $account->children()->isEmpty();
            })
            ->map(function ($item) {
                $debit = $item->journalEntries
                    ->where('trn_type', 'dr')
                    ->sum('trn_amount');

                $credit = $item->journalEntries
                    ->where('trn_type', 'cr')
                    ->sum('trn_amount');

                $item->balance = $debit - $credit;

                return $item;
            })
            ->filter(function ($account) {
                return $account->balance;
            })
            ->sortBy('type_id')
            ->groupBy('type');

        if ($request->print == true) {
            return view('basic-finance::print.income_statement', [
                'accounts_by_type' => $accounts,
                'net_profit' => $this->getNetProfit($this->startDate, $this->endDate),
                'report_title' => 'Income Statement',
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);
        }

        return view('basic-finance::reports.income_statement', [
            'accounts_by_type' => $accounts,
            'net_profit' => $this->getNetProfit($this->startDate, $this->endDate),
        ]);
    }

    public
    function balanceSheet(Request $request)
    {
        // select only asset, liability and equity type accounts
        $accounts = Account::with([
            'journalEntries' => function ($query) {
                return $query->where('trn_date', '>=', $this->startDate)
                    ->where('trn_date', '<', $this->endDate);
            }
        ])->whereIn('type_id', [
            Account::ASSET,
            Account::LIABILITY,
            Account::EQUITY
        ])
            ->get()
            ->filter(function ($account) {
                return $account->children()->isEmpty();
            })
            ->map(function ($item) {
                $debit = $item->journalEntries
                    ->where('trn_type', 'dr')
                    ->sum('trn_amount');

                $credit = $item->journalEntries
                    ->where('trn_type', 'cr')
                    ->sum('trn_amount');

                $item->balance = $debit - $credit;

                return $item;
            })
            ->filter(function ($account) {
                return $account->balance;
            });

        $netProfit = $this->getNetProfit($this->startDate, $this->endDate);

        if ($request->print == true) {
            return view('basic-finance::print.balance_sheet', [
                'assets' => $accounts->where('type_id', Account::ASSET),
                'liabilities' => $accounts->where('type_id', Account::LIABILITY),
                'equities' => $accounts->where('type_id', Account::EQUITY),
                'net_profit' => $netProfit,
                'report_title' => 'Balance Sheet',
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);
        }

        return view('basic-finance::reports.balance_sheet', [
            'assets' => $accounts->where('type_id', Account::ASSET),
            'liabilities' => $accounts->where('type_id', Account::LIABILITY),
            'equities' => $accounts->where('type_id', Account::EQUITY),
            'net_profit' => $netProfit
        ]);
    }

    public
    function cashFlowStatement(Request $request)
    {
        return view('basic-finance::reports.cash_flow_statement');
    }

    public
    function profitAndLossAccount(Request $request)
    {
        return view('basic-finance::reports.profit_and_loss_account');
    }

    private
    function getNetProfit(Carbon $startDate, Carbon $endDate)
    {
        // select only revenue and expense type accounts
        $accounts = Account::with([
            'journalEntries' => function ($query) use ($startDate, $endDate) {
                return $query->where('trn_date', '>=', $startDate)
                    ->where('trn_date', '<', $endDate);
            }
        ])->whereIn('type_id', [
            Account::INCOME,
            Account::EXPENSE
        ])
            ->get()
            ->map(function ($item) {
                $debit = $item->journalEntries
                    ->where('trn_type', 'dr')
                    ->sum('trn_amount');

                $credit = $item->journalEntries
                    ->where('trn_type', 'cr')
                    ->sum('trn_amount');

                $item->balance = $debit - $credit;

                return $item;
            });

        $revenue = $accounts->whereIn('type_id', [Account::INCOME])->sum('balance');
        $expense = $accounts->whereIn('type_id', [Account::EXPENSE])->sum('balance');
        $netProfit = abs($revenue) - $expense;

        return $netProfit;
    }

    public
    function transactions(Request $request)
    {
        $transactions = Journal::where('trn_date', '>=', $this->startDate)
            ->where('trn_date', '<', $this->endDate)
            ->orderBy('id', 'DESC')
            ->paginate();


        if ($request->print == true) {
            return view('basic-finance::print.transactions', [
                'transactions' => $transactions,
                'report_title' => 'Transaction List',
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);
        }

        return view('basic-finance::reports.transactions', [
            'transactions' => $transactions
        ]);
    }


    public function groupLedger(Request $request)
    {
        $filter['start_date'] = $start_date = date('Y-m-d', strtotime($this->startDate));
        $filter['end_date'] = $end_date = date('Y-m-d', strtotime($this->endDate));
        $filter['companyId'] = $companyId = $request->get('factory_id') ?? factoryId();
        $filter['projectId'] = $projectId = $request->get('project_id') ?? null;
        $filter['unitId'] = $unitId = $request->get('unit_id') ?? null;
        $filter['currency_id']  = $request->get('currency_type_id') ?? 0;
        $companies = Factory::all();
        $projects = [];
        $units = [];

        $parentsId = Account::query()
            ->where('is_transactional',1)
            ->where('is_active', 1)
            ->when($companyId, function($q) use ($companyId) {
                return $q->where('factory_id', $companyId);
            })
            ->whereNotNull('parent_ac')->pluck('parent_ac')->unique() ?? null;

        $parentAccounts = Account::query()
            ->when($companyId, function($q) use ($companyId) {
                return $q->where('factory_id', $companyId);
            })
            ->whereIn('id', $parentsId)->orderBy('name','asc')->pluck('name','id');

        $requestIds = $filter['account_id'] = request('account_id') ?? [$parentsId->first()];
        $account_names = Account::whereIn('id', $requestIds)->pluck('name')->implode(', ');

        $ledgersData = GroupLedgerReportService::getLedger($requestIds, $filter);


        $header = ReportViewService::for('search_info')
            ->setFactoryId($companyId)
            ->setFromDate($start_date)
            ->setToDate($end_date)
            ->render();

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::print.group_ledger_new', [
                'accounts' => $parentAccounts,
                'ledgersData' => $ledgersData,
                'filter' => $filter,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'factoryId' => $companyId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'units' => $units,
                'projects' => $projects,
                'companies' => $companies,
                'companies' => $companies,
                'header' => $header,
                'account_names' => $account_names,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('ledger-report-' . date('d:m:Y') . '.pdf');
        }

        if ($request->get('type') == 'excel') {
            $viewData = [
                'accounts' => $parentAccounts,
                'ledgersData' => $ledgersData,
                'filter' => $filter,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'factoryId' => $companyId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'units' => $units,
                'projects' => $projects,
                'companies' => $companies,
                'companies' => $companies,
                'header' => $header,
                'account_names' => $account_names,
            ];
            return Excel::download(new GroupLedgerReportExcelNew($viewData), 'ledger_report_' . date('d:m:Y') . '.xlsx');
        }

        return view(PackageConst::VIEW_NAMESPACE . '::reports.group_ledger_new', [
            'accounts' => $parentAccounts,
            'ledgersData' => $ledgersData,
            'filter' => $filter,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'factoryId' => $companyId,
            'projectId' => $projectId,
            'unitId' => $unitId,
            'units' => $units,
            'projects' => $projects,
            'companies' => $companies,
            'companies' => $companies,
            'header' => $header,
            'account_names' => $account_names,
        ]);
    }

    public function child($requestId, $startDate, $endDate, $i)
    {
        $childs_1 = Account::query()->factoryFilter()->whereNotNull('parent_ac')->whereIn('parent_ac', $requestId)->pluck('id')->unique()->toArray();
        $childs_2 = Account::query()->factoryFilter()->whereNotNull('parent_ac')->whereIn('parent_ac', $childs_1)->pluck('id')->unique();
        $values = [];
        if (count($childs_2) != 0) {
            $i++;
            $values = $this->child($childs_1, $startDate, $endDate, $i);
        } else {
            if ($i == 0) {
                $accounts = Account::query()->factoryFilter()->with([
                    'journalEntries' => function ($query) use ($startDate, $endDate) {
                        return $query->where('trn_date', '>=', $startDate)
                            ->where('trn_date', '<', $endDate);
                    }
                ])->whereIn('id', $childs_1)->get();
                $reportData['balances'] = $accounts->map(function ($account) use ($startDate, $endDate) {
                    $openingBalance = $account->openingBalance($startDate);
                    $closingBalance = $account->closingBalance($endDate);
                    return [
                        "name" => $account->name,
                        "opening_balance" => $openingBalance,
                        "closing_balance" => $closingBalance,
                    ];
                });
                $totalOpeningBalance = collect($reportData['balances'])->sum('opening_balance');
                $totalClosingBalance = collect($reportData['balances'])->sum('closing_balance');
                foreach ($childs_1 as $idWhichHaveChild) {
                    $accounts = Account::query()->factoryFilter()->with([
                        'journalEntries' => function ($query) use ($startDate, $endDate) {
                            return $query->where('trn_date', '>=', $startDate)
                                ->where('trn_date', '<', $endDate);
                        }
                    ])->whereIn('id', array($idWhichHaveChild))->get();
                    $debit = $credit = [];
                    foreach ($accounts as $account) {
                        $debitData = $account->journalEntries->filter(function ($data) {
                            return $data['trn_type'] == "dr";
                        })->flatten()->sum('trn_amount');
                        array_push($debit, $debitData);

                        $creditData = $account->journalEntries->filter(function ($data) {
                            return $data['trn_type'] == "cr";
                        })->flatten()->sum('trn_amount');
                        array_push($credit, $creditData);
                    }
                    $value['totalOpeningBalance'] = $totalOpeningBalance;
                    $value['totalClosingBalance'] = $totalClosingBalance;
                    $value['parentId'] = collect(Account::query()->factoryFilter()->whereNotNull('parent_ac')->where('id', $idWhichHaveChild)->pluck('parent_ac')->unique())->first();
                    $value['accountCode'] = collect(Account::query()->factoryFilter()->whereNotNull('parent_ac')->where('id', $idWhichHaveChild)->pluck('code')->unique())->first();
                    $value['accountHead'] = collect(Account::query()->factoryFilter()->whereNotNull('parent_ac')->where('id', $idWhichHaveChild)->pluck('name')->unique())->first();
                    $value['debit'] = collect($debit)->sum();
                    $value['credit'] = collect($credit)->sum();
                    $value['i'] = $i;
                    array_push($values, $value);
                }
            } else {
                $accounts = Account::query()->factoryFilter()->with([
                    'journalEntries' => function ($query) use ($startDate, $endDate) {
                        return $query->where('trn_date', '>=', $startDate)
                            ->where('trn_date', '<', $endDate);
                    }
                ])->whereIn('id', $childs_1)->get();
                $reportData['balances'] = $accounts->map(function ($account) use ($startDate, $endDate) {
                    $openingBalance = $account->openingBalance($startDate);
                    $closingBalance = $account->closingBalance($endDate);
                    return [
                        "name" => $account->name,
                        "opening_balance" => $openingBalance,
                        "closing_balance" => $closingBalance,
                    ];
                });
                $totalOpeningBalance = collect($reportData['balances'])->sum('opening_balance');
                $totalClosingBalance = collect($reportData['balances'])->sum('closing_balance');
                foreach ($childs_1 as $idWhichHaveChild) {
                    $accounts = Account::query()->factoryFilter()->with([
                        'journalEntries' => function ($query) use ($startDate, $endDate) {
                            return $query->where('trn_date', '>=', $startDate)
                                ->where('trn_date', '<', $endDate);
                        }
                    ])->whereIn('id', array($idWhichHaveChild))->get();
                    $debit = $credit = [];
                    foreach ($accounts as $account) {
                        $debitData = $account->journalEntries->filter(function ($data) {
                            return $data['trn_type'] == "dr";
                        })->flatten()->sum('trn_amount');
                        array_push($debit, $debitData);

                        $creditData = $account->journalEntries->filter(function ($data) {
                            return $data['trn_type'] == "cr";
                        })->flatten()->sum('trn_amount');
                        array_push($credit, $creditData);
                    }
                    $value['totalOpeningBalance'] = $totalOpeningBalance;
                    $value['totalClosingBalance'] = $totalClosingBalance;
                    $value['parentId'] = collect(Account::query()->factoryFilter()->whereNotNull('parent_ac')->where('id', $idWhichHaveChild)->pluck('parent_ac')->unique())->first();
                    $value['accountCode'] = collect(Account::query()->factoryFilter()->whereNotNull('parent_ac')->where('id', $idWhichHaveChild)->pluck('code')->unique())->first();
                    $value['accountHead'] = collect(Account::query()->factoryFilter()->whereNotNull('parent_ac')->where('id', $idWhichHaveChild)->pluck('name')->unique())->first();
                    $value['debit'] = collect($debit)->sum();
                    $value['credit'] = collect($credit)->sum();
                    $value['i'] = $i;
                    array_push($values, $value);
                }
            }
        }
        return $values;
    }

    public function provisionalLedger(Request $request)
    {
        $companyId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? '';
        $unitId = $request->get('unit_id') ?? '';
        $departmentId = $request->get('department_id') ?? '';
        $costCenterId = $request->get('cost_centre') ?? '';
        $currencyTypeId = $request->get('currency_type_id') ?? 0;
        $companies = Factory::all();
        $projects = [];
        $units = [];
        $departments = Department::query()->pluck('department', 'id')->all();
        $costCenters = CostCenter::query()->pluck('cost_center', 'id')->all();

        $accounts = Account::query()
            ->where('factory_id', $companyId)
            ->where('is_transactional', 1)
            ->where('is_active', 1)
            ->orderBy('id')
            ->get();
        $ledgerAccs = $accounts->pluck('name', 'id');
        $account = Account::query()->where('factory_id', $companyId)
            ->where('is_transactional', 1)
            ->where('is_active', 1)
            ->find(request('account_id'));
        $requestTranId = request('account_id');

       $account = $account ?? $accounts->first() ?? [];

        $vouchers =  Voucher::query()->where('factory_id', $companyId)
        ->with(['project','unit'])
        // ->whereNotIn('status_id', [Voucher::POSTED, Voucher::CANCELED])
        ->whereNotIn('status_id', [Voucher::CANCELED])
        ->whereDate('trn_date', '>=', $this->startDate)
        ->whereDate('trn_date', '<', $this->endDate)
        ->when($projectId, function($q) use ($projectId){
            return $q->where('project_id', $projectId);
        })
        ->when($projectId, function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->when($unitId, function ($query) use ($unitId) {
            $query->where('unit_id', $unitId);
        })
        ->when($departmentId, function ($query) use ($departmentId) {
            $query->whereJsonContains('details->items', ['department_id' => $departmentId]);
        })->when($costCenterId, function ($query) use ($costCenterId) {
            $query->whereJsonContains('details->items', ['const_center' => $costCenterId]);
        })
        ->when($account->id, function($query) use($account){
            return $query->where(function($q) use($account) {
                $q->where('credit_account', $account->id)
                    ->orWhere('debit_account', $account->id)
                    ->orWhereJsonContains('details->items', ['account_id' => $account->id])
                    ->orWhereJsonContains('details->items', ['account_id' => "$account->id"]);
            });
        })
        ->get();

        $provisionalLedgers  = ProvisionalLedgerReportService::formatLedger($account, $vouchers);
        $openingBalanceInfo =  ProvisionalLedgerReportService::openingLedgerBalance($account, $this->startDate ?? now(), $companyId, $projectId, $unitId, $departmentId, $costCenterId);
        $openingBalance = $balance = $openingBalanceInfo['openingBalance'];
        $openingFcBalance = $fcBalance = $openingBalanceInfo['openingFCBalance'];




        $header = ReportViewService::for('search_info')
            ->setFactoryId($companyId)
            ->setFromDate($this->startDate)
            ->setToDate($this->endDate->subDay())
            ->render();

        if ($request->get('type') == 'pdf') {
            $pdf = PDF::setOption('enable-local-file-access', true)->loadView(PackageConst::VIEW_NAMESPACE . '::print.provisional_ledger', [
                'factoryId' => $companyId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'units' => $units,
                'projects' => $projects,
                'companies' => $companies,
                'departments' => $departments,
                'cost_centers' => $costCenters,
                'openingBalance' => $openingBalance,
                'openingFcBalance' => $openingFcBalance,
                'balance' => $balance,
                'fcBalance' => $fcBalance,
                'accounts' => $ledgerAccs,
                'account' => $account,
                'requestTranId' => $requestTranId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate->subDay(),
                'header' => $header,
                'provisionalLedgers' => $provisionalLedgers,
            ])->setPaper('a4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
            return $pdf->stream('ledger-report-' . date('d:m:Y') . '.pdf');
        }

        if ($request->get('type') == 'excel') {
            $viewData = [
                'factoryId' => $companyId,
                'projectId' => $projectId,
                'unitId' => $unitId,
                'units' => $units,
                'projects' => $projects,
                'companies' => $companies,
                'departments' => $departments,
                'cost_centers' => $costCenters,
                'openingBalance' => $openingBalance,
                'openingFcBalance' => $openingFcBalance,
                'balance' => $balance,
                'fcBalance' => $fcBalance,
                'accounts' => $ledgerAccs,
                'account' => $account,
                'requestTranId' => $requestTranId,
                'currencyTypeId' => $currencyTypeId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate->subDay(),
                'provisionalLedgers' => $provisionalLedgers,
            ];
            return Excel::download(new ProvisionalLedgerReportExcel($viewData), 'provisional_ledger_report_' . date('d:m:Y') . '.xlsx');
        }


        return view(PackageConst::VIEW_NAMESPACE . '::reports.provisional_ledger', [
            'factoryId' => $companyId,
            'projectId' => $projectId,
            'unitId' => $unitId,
            'units' => $units,
            'projects' => $projects,
            'companies' => $companies,
            'departments' => $departments,
            'cost_centers' => $costCenters,
            'openingBalance' => $openingBalance,
            'openingFcBalance' => $openingFcBalance,
            'balance' => $balance,
            'fcBalance' => $fcBalance,
            'accounts' => $ledgerAccs,
            'account' => $account,
            'requestTranId' => $requestTranId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate->subDay(),
            'header' => $header,
            'provisionalLedgers' => $provisionalLedgers,
        ]);
    }


}
