<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Budgets;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\BasicFinance\DTO\BudgetDTO;
use SkylarkSoft\GoRMG\BasicFinance\Models\Journal;

class AcBudgetMonthWiseExpenseReport
{
    /**
     * @param Request $request
     * @param BudgetDTO $budgetDTO
     * @return array
     */
    public function reportData(Request $request, BudgetDTO $budgetDTO): array
    {
        $accountId = $request->get('account_id');
        $accounts = $budgetDTO->accounts($accountId);
        $fromPeriod = $budgetDTO->fromPeriod($request->get('from_month'));
        $toPeriod = $budgetDTO->toPeriod($request->get('to_month'));
        $periods = $budgetDTO->periods($fromPeriod, $toPeriod);
        $journals = $budgetDTO->journals($fromPeriod, $toPeriod);

        $reportData = [];
        foreach ($accounts as $account) {
            foreach ($periods as $period) {
                $firstDay = Carbon::make($period)->firstOfMonth()->format('Y-m-d');
                $lastDay = Carbon::make($period)->lastOfMonth()->format('Y-m-d');
                $dateCode = Carbon::make($period)->format('M-Y');
                $totalDebitAmount = collect($journals)
                    ->where('account_id', $account->id)
                    ->where('trn_type', Journal::DEBIT)
                    ->whereBetween('trn_date', [$firstDay, $lastDay])
                    ->sum('trn_amount');

                $totalCreditAmount = collect($journals)
                    ->where('account_id', $account->id)
                    ->where('trn_type', Journal::CREDIT)
                    ->whereBetween('trn_date', [$firstDay, $lastDay])
                    ->sum('trn_amount');

                $reportData[] = [
                    'account' => $account->name,
                    'month' => $dateCode,
                    'budget_amount' => $totalDebitAmount - $totalCreditAmount ?? '0.00'
                ];
            }
        }

        return $reportData;
    }
}
