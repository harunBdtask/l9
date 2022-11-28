<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services\IntegrateWithPage;

use Carbon\Carbon;

class BudgetIntegrate implements IntegrateWithPageContract
{

    public function actualDate(PageState $state): array
    {
        $order = $state->getOrder();
        $budget = $order->load('budget');

        return [
            'start_date' => Carbon::parse($budget->costing_date),
            'finish_date' => Carbon::parse($budget->costing_date)
        ];

    }
}
