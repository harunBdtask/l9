<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\BudgetUpdateServices;

use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\POItemColorSizeBreakdownService;

class FabricUpdateService
{
    public static function update($orderId)
    {
        $budget = Budget::with('fabricCosting')->where('copy_from_id', $orderId)->first();
        if ($budget) {
            if (isset($budget->fabricCosting->details['details'])) {
                $data['details'] = $budget->fabricCosting->details['details'];
                if (isset($budget->fabricCosting->details['details']['fabricForm'])) {
                    $fabricForm = collect($budget->fabricCosting->details['details']['fabricForm']);
                    $data['details']['fabricForm'] = $fabricForm->map(function (&$fabric) use ($budget) {

                        // Designing The Breakdown
                        $breakdown = POItemColorSizeBreakdownService::fabric([], $budget->id, $fabric['garment_item_id']);

                        // Data Assign if Exist
                        $fabric['greyConsForm']['details'] = collect($breakdown)->map(function ($b) use ($fabric) {
                            if (isset($fabric['greyConsForm'])) {
                                if (isset($fabric['greyConsForm']['details'])) {
                                    $greyCons = collect($fabric['greyConsForm']['details'])
                                        ->where('size_id', $b['size_id'])
                                        ->where('color_id', $b['color_id'])
                                        ->where('po_no', $b['po_no'])
                                        ->first();
                                    $prevGreyCons = collect($fabric['greyConsForm']['details'])
                                        ->where('finish_cons', '!=', 0)
                                        ->first();
                                    $b['finish_cons'] = $prevGreyCons['finish_cons'] ?? 0.00;
                                    $b['process_loss'] = $prevGreyCons['process_loss'] ?? 0.00;
                                    $b['grey_cons'] = $prevGreyCons['grey_cons'] ?? 0.00;
                                    $b['rate'] = $prevGreyCons['rate'] ?? 0.00;
                                    $b['amount'] = $prevGreyCons['amount'] ?? 0.00;
                                    $b['total_qty'] = format((float)($b['qty'] / ($b['costing_multiplier'] * $b['set_ratio']) * $b['grey_cons']));
                                    $b['total_amount'] = format((float)($b['total_qty'] * $b['rate']));
                                    if ($greyCons) {
                                        $greyCons['status'] = 0;
                                        $greyCons['qty'] = $b['qty'] ?? 0;
                                        $greyCons['total_qty'] = format((float)($greyCons['qty'] / ($greyCons['costing_multiplier'] * $greyCons['set_ratio']) * $greyCons['grey_cons']));
                                        $greyCons['total_amount'] = format((float)($greyCons['total_qty'] * $greyCons['rate']));

                                        return $greyCons;
                                    } else {
                                        return $b;
                                    }
                                } else {
                                    return $b;
                                }
                            } else {
                                return $b;
                            }
                        });

                        $fabric['grey_cons_amount'] = format(collect($fabric['greyConsForm']['details'])->avg('amount'));
                        $fabric['grey_cons_total_amount'] = format(collect($fabric['greyConsForm']['details'])->sum('total_amount'));
                        $fabric['grey_cons_total_quantity'] = format(collect($fabric['greyConsForm']['details'])->sum('total_qty'));

                        return $fabric;
                    });

                    $budgetCostingDetails = BudgetCostingDetails::where('budget_id', $budget->id)->where('type', 'fabric_costing')->first();
                    $data['calculation'] = array_merge($budget->fabricCosting->details['calculation'], self::updateCalculation($data['details']['fabricForm']));
                    $budgetCostingDetails->update([
                        'details' => $data,
                    ]);
                }
            }
        }
    }

    private static function updateCalculation($data): array
    {
        $calculation['qty_avg'] = collect($data)->avg('qty');
        $calculation['qty_sum'] = collect($data)->sum('qty');
        $calculation['rate_avg'] = collect($data)->avg('rate');
        $calculation['rate_sum'] = collect($data)->sum('rate');
        $calculation['amount_avg'] = collect($data)->avg('amount');
        $calculation['amount_sum'] = collect($data)->sum('amount');
        $calculation['total_amount_avg'] = collect($data)->avg('total_amount');
        $calculation['total_amount_sum'] = collect($data)->sum('total_amount');

        return $calculation;
    }
}
