<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\BasicFinance\Filters\Filter;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\Journal;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;

class ProjectWiseCashReportService
{
    public function getReportData(Request $request): array
    {
        $factoryId = $request->get('factory_id');
        $projectId = $request->get('project_id')
            ? $request->get('project_id')
            : $this->projects($request)->pluck('id');
        $accountId = $request->get('account_id')
            ? $request->get('account_id')
            : $this->cashInHandAccounts($request)->pluck('id');
        $fromDate = $request->get('from_date')
            ? $request->get('from_date')
            : Carbon::now()->format('Y-m-d');
        $toDate = $request->get('to_date')
            ? $request->get('to_date')
            : Carbon::now()->format('Y-m-d');

        $journals = Journal::query()
            ->select('id', 'trn_date', 'account_id', 'account_code', 'project_id', 'trn_type',
                'factory_id', 'trn_amount')
            ->where('factory_id', $factoryId)
            ->when($projectId, Filter::applyWhereInFilter('project_id', $projectId))
            ->when($accountId, Filter::applyWhereInFilter('account_id', $accountId));

        $openingJournals = (clone $journals)
            ->where('trn_date', '<', $fromDate)
            ->get()
            ->groupBy('project_id')->flatMap(function ($groupByProjects) {
                return collect($groupByProjects)->groupBy('account_id')->map(function ($collection) {
                    $totalDebitAmount = $collection->where('trn_type', 'dr')->sum('trn_amount');
                    $totalCreditAmount = $collection->where('trn_type', 'cr')->sum('trn_amount');
                    $collection = $collection->first();
                    $collection['trn_amount'] = $totalDebitAmount - $totalCreditAmount;

                    return $collection;
                });
            });

        $currentJournals = (clone $journals)
            ->whereBetween('trn_date', [$fromDate, $toDate])
            ->get()
            ->groupBy('project_id')->flatMap(function ($groupByProjects) {
                return collect($groupByProjects)->groupBy('account_id')->map(function ($collection) {
                    $totalDebitAmount = $collection->where('trn_type', 'dr')->sum('trn_amount');
                    $totalCreditAmount = $collection->where('trn_type', 'cr')->sum('trn_amount');
                    $collection = $collection->first();
                    $collection['total_debit_amount'] = $totalDebitAmount;
                    $collection['total_credit_amount'] = $totalCreditAmount;
                    $collection['trn_amount'] = $totalDebitAmount - $totalCreditAmount;

                    return $collection;
                });
            });

        $projects = $this->projects($request);
        $accounts = $this->cashInHandAccounts($request);
        $accountSubTotal = [];

        $reportData = $projects->map(function ($project) use ($accounts, $openingJournals, $currentJournals, &$accountSubTotal) {
            return $accounts->map(function ($account) use ($project, $openingJournals, $currentJournals, &$accountSubTotal) {
                $openingBalance = collect($openingJournals)->where('account_id', $account->id)
                    ->where('project_id', $project->id)
                    ->sum('trn_amount');

                $totalDebitAmount = collect($currentJournals)->where('account_id', $account->id)
                    ->where('project_id', $project->id)
                    // ->where('trn_type', 'dr')
                    ->sum('total_debit_amount');

                $totalCreditAmount = collect($currentJournals)->where('account_id', $account->id)
                    ->where('project_id', $project->id)
                    // ->where('trn_type', 'cr')
                    ->sum('total_credit_amount');

                $closingBalance = ($openingBalance + $totalDebitAmount) - $totalCreditAmount;

                if (array_key_exists($account->id, $accountSubTotal)) {
                    $accountSubTotal[$account->id]['opening_balance'] += $openingBalance;
                    $accountSubTotal[$account->id]['total_debit_balance'] += $totalDebitAmount;
                    $accountSubTotal[$account->id]['total_credit_balance'] += $totalCreditAmount;
                    $accountSubTotal[$account->id]['closing_balance'] += $closingBalance;
                } else {
                    $accountSubTotal[$account->id]['name'] = $account->name;
                    $accountSubTotal[$account->id]['opening_balance'] = $openingBalance;
                    $accountSubTotal[$account->id]['total_debit_balance'] = $totalDebitAmount;
                    $accountSubTotal[$account->id]['total_credit_balance'] = $totalCreditAmount;
                    $accountSubTotal[$account->id]['closing_balance'] = $closingBalance;
                }

                return [
                    'account_id' => $account->id,
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'project_id' => $project->id,
                    'project_name' => $project->project,
                    'opening_balance' => $openingBalance,
                    'total_debit_amount' => $totalDebitAmount,
                    'total_credit_amount' => $totalCreditAmount,
                    'closing_balance' => $closingBalance,
                ];
            });
        });

        return [
            'reportData' => $reportData,
            'accountSubTotal' => $accountSubTotal,
        ];
    }

    public function cashInHandAccounts(Request $request)
    {
        $factoryId = $request->get('factory_id');
        $accountId = $request->get('account_id');

        return Account::query()
            ->where('parent_ac', Account::CASH_IN_HAND_ACCOUNT)
            ->where('factory_id', $factoryId)
            ->when($accountId, Filter::applyWhereInFilter('id', $accountId))
            ->get();
    }

    public function projects(Request $request)
    {
        $projectId = $request->get('project_id');
        $factoryId = $request->get('factory_id');

        return Project::query()
            ->where('factory_id', $factoryId)
            ->when($projectId, Filter::applyWhereInFilter('id', $projectId))
            ->get();
    }
}
