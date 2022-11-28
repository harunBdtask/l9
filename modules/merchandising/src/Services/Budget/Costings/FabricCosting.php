<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings;

use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetBreakDown;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetCostingService;

class FabricCosting implements CostingContract
{
    public function format($budget, $budgetId, $type): array
    {
        $yarnCostings = [];

        if (isset($budget->details['details'])) {
            $fabric_costings = collect($budget->details['details']['fabricForm'])
                ->map(function ($value) use ($budgetId) {
                    if (!array_key_exists('greyConsForm', $value)) {
                        $service = new BudgetBreakDown();
                        $service->setBudgetId($budgetId);
                        $service->setItemId($value['garment_item_id']);
                        $service->setDiaAvg($value['fabricConsumptionCalculation']['dia_avg']);
                        $service->setConsAvg($value['fabricConsumptionCalculation']['cons_avg']);
                        $service->setProcessAvg($value['fabricConsumptionCalculation']['process_avg']);
                        $service->setRateAvg($value['fabricConsumptionCalculation']['rate_avg']);
                        $break_down = BudgetCostingService::itemWiseFabricBreakDown($service);
                        $calculation['finish_cons_sum'] = collect($break_down)->pluck('finish_cons')->sum();
                        $calculation['finish_cons_avg'] = collect($break_down)->pluck('finish_cons')->avg();
                        $calculation['process_loss_sum'] = collect($break_down)->pluck('process_loss')->sum();
                        $calculation['process_loss_avg'] = collect($break_down)->pluck('process_loss')->avg();
                        $calculation['grey_cons_sum'] = collect($break_down)->pluck('grey_cons')->sum();
                        $calculation['grey_cons_avg'] = collect($break_down)->pluck('grey_cons')->avg();
                        $calculation['rate_sum'] = collect($break_down)->pluck('rate')->sum();
                        $calculation['rate_avg'] = collect($break_down)->pluck('rate')->avg();
                        $calculation['amount_sum'] = collect($break_down)->pluck('amount')->sum();
                        $calculation['amount_avg'] = collect($break_down)->pluck('amount')->avg();
                        $calculation['pcs_sum'] = collect($break_down)->pluck('pcs')->sum();
                        $calculation['pcs_avg'] = collect($break_down)->pluck('pcs')->avg();
                        $calculation['qty_sum'] = collect($break_down)->pluck('total_qty')->sum();
                        $calculation['qty_avg'] = collect($break_down)->pluck('total_qty')->avg();
                        $calculation['total_amount_sum'] = collect($break_down)->pluck('total_amount')->sum();
                        $calculation['total_amount_avg'] = collect($break_down)->pluck('total_amount')->avg();
                        $value['grey_cons'] = $calculation['grey_cons_avg'];
                        $value['grey_cons_rate'] = $calculation['rate_avg'];
                        $value['grey_cons_amount'] = $calculation['amount_avg'];
                        $value['grey_cons_total_quantity'] = $calculation['qty_sum'];
                        $value['grey_cons_total_amount'] = $calculation['total_amount_sum'];

                        return array_merge($value, [
                            'greyConsForm' => [
                                'details' => $break_down,
                                'calculation' => $calculation,
                            ],
                        ]);
                    }

                    return $value;
                });

            $yarnCostings = collect($budget->details['details']['yarnCostForm'])
                ->map(function ($yarnCost) {
                    $processLoss = $yarnCost['process_loss'] ?? null;
                    $processLossPercentage = ($yarnCost['cons_qty'] * $processLoss) / 100;
                    $consQtyWithPl = $processLossPercentage + $yarnCost['cons_qty'];
                    return array_merge($yarnCost, [
                        'process_loss' => $yarnCost['process_loss'] ?? null,
                        'cons_qty_with_pl' => number_format($consQtyWithPl, 4),
                    ]);
                });
        }
        $data['id'] = $budget->id ?? null;
        $data['budget_id'] = $budgetId;
        $data['type'] = $type;
        $data['details'] = [
            'details' => [
                'fabricForm' => $fabric_costings ?? null,
                'yarnCostForm' => isset($budget->details['details']) ? $yarnCostings : [],
                'conversionCostForm' => isset($budget->details['details']) ? $budget->details['details']['conversionCostForm'] : [],
            ],
            'calculation' => isset($budget->details) ? $budget->details['calculation'] : [],
        ];

        return $data;
    }
}
