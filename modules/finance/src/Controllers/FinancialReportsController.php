<?php

namespace SkylarkSoft\GoRMG\Finance\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Finance\Models\Unit;
use SkylarkSoft\GoRMG\Finance\Models\AcUnit;
use SkylarkSoft\GoRMG\Finance\Models\Account;
use SkylarkSoft\GoRMG\Finance\Models\Journal;
use SkylarkSoft\GoRMG\Finance\Models\AcCompany;
use SkylarkSoft\GoRMG\Finance\Models\CostCenter;
use SkylarkSoft\GoRMG\Finance\Models\AcDepartment;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Finance\Services\AccountGroupService;
use SkylarkSoft\GoRMG\Finance\Services\Reports\TrialBalance\TrialBalanceReportStrategy;
use SkylarkSoft\GoRMG\Finance\Models\Voucher;
use Illuminate\Support\Facades\Auth;

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

    public function trialBalance(Request $request)
    {
        $accounts = Journal::with('account')
            ->where('trn_date', '>=', $this->startDate)
            ->where('trn_date', '<', $this->endDate)
            ->orderBy('account_id')
            ->get()
            ->groupBy('account_id')
            ->map(function ($entries) {
                $account = $entries->first()->account;
                $account->balance = $entries->sum(function ($item) {
                    return $item->trn_type == 'cr' ? (-1) * $item->trn_amount : $item->trn_amount;
                });

                return $account;
            });

        $totalDebit = $accounts->filter(function ($item) {
            return $item->balance >= 0;
        })->sum('balance');

        $totalCredit = $accounts->filter(function ($item) {
            return $item->balance < 0;
        })->sum('balance');

        if ($request->print == true) {
            return view('finance::print.trial_balance', [
                'accounts' => $accounts,
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'report_title' => 'Trial Balance',
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);
        }

        return view('finance::reports.trial_balance', [
            'accounts' => $accounts,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit
        ]);
    }

    public function trialBalanceV2(Request $request)
    {
        $companyId = $request->get('company_id') ?? '';
        $unitId = $request->get('unit_id') ?? '';
        $costCenterId = $request->get('cost_centre') ?? '';
        $groupId = $request->get('group_id') ?? null;
        $companies = Factory::query()->pluck('factory_name as name', 'id');
        $cost_centers = CostCenter::query()->pluck('cost_center as name', 'id');
        $units = [];
        $departments = [];

        if ($companyId) {
            $units = Unit::query()->where('factory_id', $companyId)->pluck('unit', 'id');
        }

        if ($unitId) {
            $departments = AcDepartment::query()->where('ac_company_id', $companyId)
                ->where('ac_unit_id', $unitId)
                ->pluck('name', 'id');
        }

       $groups = collect(AccountGroupService::groups())->pluck('text', 'id');

        $reportData = (new TrialBalanceReportStrategy)->setGroupId($groupId)
            ->setData($request)
            ->generateReport();

        return view('finance::reports.trial_balance_v2', [
            'units' => $units,
            'groups' => $groups,
            'unit_id' => $unitId,
            'companies' => $companies,
            'end_date' => $this->endDate,
            'report_data' => $reportData,
            'departments' => $departments,
            'start_date' => $this->startDate,
            'cost_center_id' => $costCenterId,
            'cost_centers' => $cost_centers,
        ]);
    }

    public function ledger(Request $request)
    {
        $accounts = Account::with([
            'journalEntries' => function ($query) {
                return $query->where('trn_date', '>=', $this->startDate)
                    ->where('trn_date', '<', $this->endDate);
            }
        ])
            ->orderBy('id')
            ->get()
            ->filter(function ($account) {
                return $account->children()->isEmpty();
            });

        $account = Account::with([
            'journalEntries' => function ($query) {
                return $query->where('trn_date', '>=', $this->startDate)
                    ->where('trn_date', '<', $this->endDate)
                    ->orderBy('trn_date');
            }
        ])->find(request('account_id'));
        $account = $account ?? $accounts->first();

        if ($request->print == true) {
            return view('finance::print.ledger', [
                'accounts' => $accounts,
                'account' => $account,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);
        }

        return view('finance::reports.ledger', [
            'accounts' => $accounts,
            'account' => $account,
        ]);
    }

    public function ledgerReportV2(Request $request)
    {
        $companyId = $request->get('company_id') ?? '';
        $costCenterId = $request->get('cost_centre') ?? '';
        $account_id = $request->get('account_id') ?? '';
        $companies = Factory::query()->pluck('factory_name as name', 'id');
        $cost_centers = CostCenter::query()->pluck('cost_center as name', 'id');


        $ledgerAccount = Account::query()
            ->has('accountInfo.parentAccount')
            ->has('accountInfo.groupAccount')
            ->has('accountInfo.controlAccount')
            ->pluck('name','id');

       $account = Account::query()
            ->with([
                'journalEntries' => function ($query) use ($companyId,$costCenterId) {
                    return $query->whereBetween('trn_date', [$this->startDate, $this->endDate])
                        ->when($companyId, function ($query) use ($companyId) {
                            return $query->where('factory_id', $companyId);
                        })->when($costCenterId, function ($query) use ($costCenterId) {
                            $query->where('cost_center_id', $costCenterId);
                        });
                }
            ])
            ->find($request->get('account_id'));

        $openingBalance = $account ? $account->openingBalance($this->startDate) : 0.00;
        return view('finance::reports.ledger_v2', [
            'companies' => $companies,
            'account' => $account,
            'cost_centers' => $cost_centers,
            'ledger_accounts' => $ledgerAccount,
            'openingBalance' => $openingBalance,
        ]);
    }

    public function voucherDetails(Request $request)
    {
        // return true;
        $voucherNo = $request->get('voucher_no');
        $account = $request->get('account');
        $voucher = Voucher::query()->where('factory_id', Auth::user()->factory_id)->where('voucher_no', $voucherNo)->first();
        return view('finance::reports.voucher_details_table', [
            'voucher' => $voucher, 'account' => $account,
        ]);
    }

    public function cashBook(Request $request)
    {
        return view('finance::reports.cash_book');
    }

    public function receiptsAndPayments(Request $request)
    {
        return view('finance::reports.receipt_and_payment_statement');
    }

    public function incomeStatement(Request $request)
    {
        // select only revenue and expense type accounts
        $accounts = Account::with([
            'journalEntries' => function ($query) {
                return $query->where('trn_date', '>=', $this->startDate)
                    ->where('trn_date', '<', $this->endDate);
            }
        ])->whereIn('type_id', [
            Account::REVENUE_OP,
            Account::REVENUE_NOP,
            Account::EXPENSE_OP,
            Account::EXPENSE_NOP
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
            return view('finance::print.income_statement', [
                'accounts_by_type' => $accounts,
                'net_profit' => $this->getNetProfit($this->startDate, $this->endDate),
                'report_title' => 'Income Statement',
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);
        }

        return view('finance::reports.income_statement', [
            'accounts_by_type' => $accounts,
            'net_profit' => $this->getNetProfit($this->startDate, $this->endDate),
        ]);
    }

    public function balanceSheet(Request $request)
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
            return view('finance::print.balance_sheet', [
                'assets' => $accounts->where('type_id', Account::ASSET),
                'liabilities' => $accounts->where('type_id', Account::LIABILITY),
                'equities' => $accounts->where('type_id', Account::EQUITY),
                'net_profit' => $netProfit,
                'report_title' => 'Balance Sheet',
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);
        }

        return view('finance::reports.balance_sheet', [
            'assets' => $accounts->where('type_id', Account::ASSET),
            'liabilities' => $accounts->where('type_id', Account::LIABILITY),
            'equities' => $accounts->where('type_id', Account::EQUITY),
            'net_profit' => $netProfit
        ]);
    }

    public function cashFlowStatement(Request $request)
    {
        return view('finance::reports.cash_flow_statement');
    }

    public function profitAndLossAccount(Request $request)
    {
        return view('finance::reports.profit_and_loss_account');
    }

    private function getNetProfit(Carbon $startDate, Carbon $endDate)
    {
        // select only revenue and expense type accounts
        $accounts = Account::with([
            'journalEntries' => function ($query) use ($startDate, $endDate) {
                return $query->where('trn_date', '>=', $startDate)
                    ->where('trn_date', '<', $endDate);
            }
        ])->whereIn('type_id', [
            Account::REVENUE_OP,
            Account::REVENUE_NOP,
            Account::EXPENSE_OP,
            Account::EXPENSE_NOP
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

        $revenue = $accounts->whereIn('type_id', [Account::REVENUE_OP, Account::REVENUE_NOP])->sum('balance');
        $expense = $accounts->whereIn('type_id', [Account::EXPENSE_OP, Account::EXPENSE_NOP])->sum('balance');
        $netProfit = abs($revenue) - $expense;

        return $netProfit;
    }

    public function transactions(Request $request)
    {
        $transactions = Journal::where('trn_date', '>=', $this->startDate)
            ->where('trn_date', '<', $this->endDate)
            ->orderBy('id', 'DESC')
            ->paginate();


        if ($request->print == true) {
            return view('finance::print.transactions', [
                'transactions' => $transactions,
                'report_title' => 'Transaction List',
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);
        }

        return view('finance::reports.transactions', [
            'transactions' => $transactions
        ]);
    }
}
