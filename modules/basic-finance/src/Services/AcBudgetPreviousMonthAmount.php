<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services;

use SkylarkSoft\GoRMG\BasicFinance\Models\AcBudgetApproval;
use SkylarkSoft\GoRMG\BasicFinance\Models\AcBudgetDetail;

class AcBudgetPreviousMonthAmount
{
    public static function prevBudgetAmount($previousCode, $accountId)
    {
        return AcBudgetDetail::query()->factoryFilter()->whereRelation('bfBudget', 'code', $previousCode)
                ->where('bf_account_id', $accountId)
                ->first()['amount'] ?? 0.0000;
    }

    public static function prevApprovalAmount($accountId, $prevMonthCode): float
    {
        return AcBudgetApproval::query()->factoryFilter()->where('bf_account_id', $accountId)
                ->where('code', $prevMonthCode)
                ->sum('apprv_amount') ?? 0.0000;
    }
}
