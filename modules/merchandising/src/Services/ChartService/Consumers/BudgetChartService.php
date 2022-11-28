<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Consumers;

use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Services\ChartService\Concerns\ChartServiceContract;

class BudgetChartService extends ChartServiceContract
{
    private $values = [];

    public function getLevels(): array
    {
        return [
            'Total Budgets',
            'Approved Budgets',
            'UnApproved Budgets',
        ];
    }

    public function getValues(): array
    {
        if (!$this->values) {
            $totalBudgets = Budget::all()->count();
            $approved = Budget::query()->where('is_approve', 1)->count();
            $unApproved = Budget::query()
                ->where('is_approve', 0)
                ->orWhereNull('is_approve')
                ->count();

            $this->values = [
                $totalBudgets,
                $approved,
                $unApproved,
            ];
        }

        return $this->values;

    }

    public function renderIn(): string
    {
        return 'merchandising::chart-service.chart';
    }

    public function getType(): string
    {
        return self::BAR;
    }
}
