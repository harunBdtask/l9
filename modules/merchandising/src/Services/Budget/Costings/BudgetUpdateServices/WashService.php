<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\BudgetUpdateServices;

use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\POItemColorSizeBreakdownService;

class WashService
{
    public static function update($orderId)
    {
        $budget = Budget::with('washCosting')
            ->where('copy_from_id', $orderId)
            ->first();
        if ($budget) {
            if (isset($budget->washCosting->details['details'])) {
                $data['details'] = $budget->washCosting->details['details'];
                if (isset($budget->washCosting->details['details'])) {
                    $washCostingForm = collect($budget->washCosting->details['details']);
                    $data['details'] = $washCostingForm->map(function (&$wash) use ($budget) {

                        // Designing The Breakdown
                        $request = [
                            'cons_gmts' => $wash['consumption'] ?? 0,
                            'rate' => $wash['consumption_rate'] ?? 0,
                            'amount' => $wash['consumption_amount'] ?? 0,
                        ];
                        $breakdown = POItemColorSizeBreakdownService::wash($budget->id, $request);

                        // Data Assign if Exist
                        $wash['breakdown']['details'] = collect($breakdown)->map(function ($b) use ($wash) {
                            if (isset($wash['breakdown'])) {
                                if (isset($wash['breakdown']['details'])) {
                                    $breakDown = collect($wash['breakdown']['details'])
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

                        $wash['consumption'] = format(collect($wash['breakdown']['details'])->avg('cons_gmts'));
                        $wash['consumption_rate'] = format(collect($wash['breakdown']['details'])->avg('rate'));
                        $wash['consumption_amount'] = format(collect($wash['breakdown']['details'])->avg('amount'));

                        return $wash;
                    });
                    $data['calculation'] = array_merge($budget->washCosting->details['calculation'], self::updateCalculation($data['details']));
                    BudgetCostingDetails::where('budget_id', $budget->id)
                        ->where('type', 'wash_cost')
                        ->update([
                            'details' => $data,
                        ]);
                }
            }
        }
    }

    private static function updateCalculation($data): array
    {
        $calculation['consumption_sum'] = format(collect($data)->sum('consumption'));
        $calculation['consumption_rate_sum'] = format(collect($data)->sum('consumption_rate'));
        $calculation['consumption_amount_sum'] = format(collect($data)->sum('consumption_amount'));

        return $calculation;
    }
}
