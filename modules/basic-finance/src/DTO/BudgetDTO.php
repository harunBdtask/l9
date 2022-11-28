<?php

namespace SkylarkSoft\GoRMG\BasicFinance\DTO;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use SkylarkSoft\GoRMG\BasicFinance\Models\Account;
use SkylarkSoft\GoRMG\BasicFinance\Models\Journal;
use SkylarkSoft\GoRMG\BasicFinance\Models\AcBudget;

class BudgetDTO
{
    /**
     * @param $month
     * @return array
     */
    public function explodeMonthYear($month): array
    {
        $explode = explode('-', $month);

        return [
            'year' => $explode[0],
            'month' => $explode[1],
        ];
    }

    /**
     * @param $accountId
     * @return array|Builder[]|Collection
     */
    public function accounts($accountId)
    {
        return Account::query()->factoryFilter()
            ->whereIn('type_id', [Account::EXPENSE_OP, Account::EXPENSE_NOP])
            ->when($accountId, function (Builder $query) use ($accountId) {
                $query->where('id', $accountId);
            })->get();
    }

    /**
     * @param $fromMonth
     * @return Carbon|false
     */
    public function fromPeriod($fromMonth)
    {
        return Carbon::create($fromMonth . '-01');
    }

    /**
     * @param $toMonth
     * @return Carbon|false
     */
    public function toPeriod($toMonth)
    {
        return Carbon::create($toMonth . '-30');
    }

    /**
     * @param $fromMonth
     * @param $toMonth
     * @return CarbonPeriod
     */
    public function periods($fromMonth, $toMonth): CarbonPeriod
    {
        return CarbonPeriod::create($this->fromPeriod($fromMonth), '1 month', $this->toPeriod($toMonth));
    }

    /**
     * @param $from
     * @param $to
     * @return Builder[]|Collection
     */
    public function budgetDetails($from, $to)
    {
        return AcBudget::query()
            ->factoryFilter()
            ->with('approvals')
            ->whereBetween('year', [$from['year'], $to['year']])
            ->get()
            ->pluck('approvals')
            ->flatten();
    }

    /**
     * @param $fromPeriod
     * @param $toPeriod
     * @return Builder[]|Collection
     */
    public function journals($fromPeriod, $toPeriod)
    {
        return Journal::query()->factoryFilter()
            ->whereBetween('trn_date', [$fromPeriod, $toPeriod])
            ->get();
    }
}
