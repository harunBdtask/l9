<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Budgets;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\BasicFinance\DTO\BudgetDTO;

class AcBudgetMonthWiseReport
{
    /**
     * @param Request $request
     * @param BudgetDTO $budgetDTO
     * @return array
     */
    public function reportData(Request $request, BudgetDTO $budgetDTO): array
    {
        $accountId = $request->get('account_id');
        $from = $budgetDTO->explodeMonthYear($request->get('from_month'));
        $to = $budgetDTO->explodeMonthYear($request->get('to_month'));
        $accounts = $budgetDTO->accounts($accountId);
        $fromPeriod = $budgetDTO->fromPeriod($request->get('from_month'));
        $toPeriod = $budgetDTO->toPeriod($request->get('to_month'));
        $periods = $budgetDTO->periods($fromPeriod, $toPeriod);
        $budgetApprovalDetails = $budgetDTO->budgetDetails($from, $to);
        $reportData = [];
        foreach ($accounts as $account) {
            foreach ($periods as $period) {
                $dateCode = Carbon::make($period)->format('M-Y');
                $budgetApproval = collect($budgetApprovalDetails)
                    ->where('bf_account_id', $account->id)
                    ->where('code', $dateCode)
                    ->first();

                $reportData[] = [
                    'account' => $account->name,
                    'month' => $dateCode,
                    'budget_amount' => $budgetApproval->apprv_amount ?? '0.00'
                ];
            }
        }

        return $reportData;
    }
}
