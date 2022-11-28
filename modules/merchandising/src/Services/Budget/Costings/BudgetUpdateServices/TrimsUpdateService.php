<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\BudgetUpdateServices;

use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\POItemColorSizeBreakdownService;

class TrimsUpdateService
{
    public static function update($orderId)
    {
        $budget = Budget::with('trimCosting')->where('copy_from_id', $orderId)->first();
        if ($budget) {
            if (isset($budget->trimCosting->details['details'])) {
                $data['details'] = $budget->trimCosting->details['details'];
                if (isset($budget->trimCosting->details['details'])) {
                    $trimsForm = collect($budget->trimCosting->details['details']);
                    $data['details'] = $trimsForm->map(function (&$trims) use ($budget) {

                        // Designing The Breakdown
                        $request = [
                            'cons_gmts' => $trims['cons_gmts'] ?? 0,
                            'rate' => $trims['rate'] ?? 0,
                            'amount' => $trims['amount'] ?? 0,
                            'ex_cons_percent' => $trims['ex_cons_percent'] ?? 0,
                        ];

                        $breakdown = POItemColorSizeBreakdownService::trims($budget->id, $request);
//                        dd($breakdown);
                        // Data Assign if Exist
                        $trims['breakdown']['details'] = collect($breakdown)->map(function ($b) use ($trims) {
                            if (isset($trims['breakdown'])) {
                                if (isset($trims['breakdown']['details'])) {
                                    $breakDown = collect($trims['breakdown']['details'])
                                        ->where('po_no', $b['po_no'])
                                        ->where('item_id', $b['item_id'])
                                        ->where('size_id', $b['size_id'])
                                        ->where('color_id', $b['color_id'])
                                        ->first();
                                    if ($breakDown) {
                                        $breakDown['status'] = 0;
                                        $breakDown['qty'] = $b['qty'] ?? 0;
                                        $total_quantity = $breakDown['qty'] / ($breakDown['costing_multiplier'] * $breakDown['set_ratio']) * $breakDown['cons_gmts'];
                                        $breakDown['total_qty'] = format((float)$total_quantity);
                                        $breakDown['total_amount'] = format((float)$total_quantity * $breakDown['rate']);

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
                        $trims['amount'] = format(collect($trims['breakdown']['details'])->avg('amount'));
                        $trims['total_amount'] = format(collect($trims['breakdown']['details'])->sum('total_amount'));
                        $trims['total_quantity'] = format(collect($trims['breakdown']['details'])->sum('total_qty'));

                        return $trims;
                    });
                    $data['calculation'] = array_merge($budget->trimCosting->details['calculation'], self::updateCalculation($data['details']));
                    BudgetCostingDetails::where('budget_id', $budget->id)
                        ->where('type', 'trims_costing')
                        ->update([
                            'details' => $data,
                        ]);
                }
            }
        }
    }

    private static function updateCalculation($data): array
    {
        $calculation['pcs_avg'] = collect($data)->avg('pcs');
        $calculation['pcs_sum'] = collect($data)->sum('pcs');
        $calculation['rate_avg'] = collect($data)->avg('rate');
        $calculation['rate_sum'] = collect($data)->sum('rate');
        $calculation['amount_avg'] = collect($data)->avg('amount');
        $calculation['amount_sum'] = collect($data)->sum('amount');
        $calculation['total_qty_avg'] = collect($data)->avg('total_qty');
        $calculation['total_qty_sum'] = collect($data)->sum('total_qty');
        $calculation['total_amount_avg'] = collect($data)->avg('total_amount');
        $calculation['total_amount_sum'] = collect($data)->sum('total_amount');

        return $calculation;
    }
}
