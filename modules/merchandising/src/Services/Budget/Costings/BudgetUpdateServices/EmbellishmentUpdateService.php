<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\BudgetUpdateServices;

use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\POItemColorSizeBreakdownService;

class EmbellishmentUpdateService
{
    public static function update($orderId)
    {
        $budget = Budget::with('embellishmentCosting')
            ->where('copy_from_id', $orderId)
            ->first();
        if ($budget) {
            if (isset($budget->embellishmentCosting->details['details'])) {
                $data['details'] = $budget->embellishmentCosting->details['details'];
                if (isset($budget->embellishmentCosting->details['details'])) {
                    $embellishmentForm = collect($budget->embellishmentCosting->details['details']);
                    $data['details'] = $embellishmentForm->map(function (&$embellishment) use ($budget) {

                        // Designing The Breakdown
                        $request = [
                            'cons_gmts' => $embellishment['consumption'] ?? 0,
                            'rate' => $embellishment['consumption_rate'] ?? 0,
                            'amount' => $embellishment['consumption_amount'] ?? 0,
                        ];
                        $breakdown = POItemColorSizeBreakdownService::embellishment($budget->id, $request);

                        // Data Assign if Exist
                        $embellishment['breakdown']['details'] = collect($breakdown)->map(function ($b) use ($embellishment) {
                            if (isset($embellishment['breakdown'])) {
                                if (isset($embellishment['breakdown']['details'])) {
                                    $breakDown = collect($embellishment['breakdown']['details'])
                                        ->where('po_no', $b['po_no'])
                                        ->where('item_id', $b['item_id'])
                                        ->where('size_id', $b['size_id'])
                                        ->where('color_id', $b['color_id'])
                                        ->first();
                                    if ($breakDown) {
                                        $breakDown['status'] = 0;
                                        $breakDown['qty'] = $b['qty'] ?? 0;
                                        $total_quantity = $breakDown['qty'] / ($breakDown['costing_multiplier'] * $breakDown['set_ratio']) * $breakDown['cons_gmts'];
                                        $breakDown['total_qty'] = format((float) $total_quantity);
                                        $breakDown['total_amount'] = format((float) $total_quantity * $breakDown['rate']);

                                        return $breakDown;
                                    } else {
                                        return array_merge([
                                            'status' => 1,
                                        ], $b);
                                    }
                                } else {
                                    return $b;
                                }
                            } else {
                                return $b;
                            }
                        });

                        $embellishment['consumption'] = format(collect($embellishment['breakdown']['details'])->avg('cons_gmts'));
                        $embellishment['consumption_rate'] = format(collect($embellishment['breakdown']['details'])->avg('rate'));
                        $embellishment['consumption_amount'] = format(collect($embellishment['breakdown']['details'])->avg('amount'));

                        return $embellishment;
                    });
                    $data['calculation'] = array_merge($budget->embellishmentCosting->details['calculation'], self::updateCalculation($data['details']));
                    BudgetCostingDetails::where('budget_id', $budget->id)
                        ->where('type', 'embellishment_cost')
                        ->update([
                            'details' => $data,
                        ]);
                }
            }
        }
    }

    private static function updateCalculation($data): array
    {
        $calculation['consumption_sum'] = number_format(collect($data)->sum('consumption'), 4);
        $calculation['consumption_rate_sum'] = number_format(collect($data)->sum('consumption_rate'), 4);
        $calculation['consumption_amount_sum'] = number_format(collect($data)->sum('consumption_amount'), 4);

        return $calculation;
    }
}
