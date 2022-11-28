<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget;

use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Country;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\VariableSettings\VariableService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class BudgetCostingService
{
    /**
     * @return array
     */
    public function costingTypes(): array
    {
        return collect(BudgetCostingDetails::COSTING_TYPES)->toArray();
    }

    public function fabricColors()
    {
        return Color::pull('fabric_color')->get(['id', 'name as text']);
    }

    public function countries()
    {
        return Country::all();
    }

    /**
     * @param BudgetBreakDown $budgetBreakDown
     * @return array
     */
    public static function itemWiseFabricBreakDown(BudgetBreakDown $budgetBreakDown): array
    {
        $budgetData = Budget::with([
            'order.purchaseOrders.poDetails' => function ($query) use ($budgetBreakDown) {
                $query->where('garments_item_id', $budgetBreakDown->getItemId());
            },
        ])->findOrFail($budgetBreakDown->getBudgetId());

        $purchaseOrders = $budgetData->order->purchaseOrders->map(function ($po) {
            return [
                'id' => $po['id'],
                'po_no' => $po['po_no'],
                'poDetails' => $po->poDetails->map(function ($poDetail) {
                    return [
                        'colors' => Color::query()->whereIn('id', $poDetail['colors'])->get(),
                        'sizes' => Size::query()->whereIn('id', $poDetail['sizes'])->get(),
                        'quantity_matrix' => $poDetail['quantity_matrix'],
                    ];
                }),
            ];
        });

        // Designing the data
        $breakdown = [];
        $item_ratio = '';
        $costing_multiplier = $budgetData->costing_multiplier;
        $pcs = 0;
        if (isset($budgetData->order->item_details)) {
            $item_ratio = collect($budgetData->order->item_details['details'])
                ->where('item_id', $budgetBreakDown->getItemId())
                ->first()['item_ratio'];
            $pcs = $item_ratio * $costing_multiplier;
        }

        $variableSetting = VariableService::getVariableSettings($budgetData->factory_id, $budgetData->buyer_id);
        if (isset($variableSetting->variables_details)) {
            $variable = collect($variableSetting->variables_details)->get('fabric_total_qty_calculation');
        } else {
            $variable = null;
        }

        foreach ($purchaseOrders as $purchaseOrder) {
            foreach ($purchaseOrder['poDetails'] as $poDetail) {
                foreach ($poDetail['colors'] as $color) {
                    foreach ($poDetail['sizes'] as $size) {
                        if ($variable == 'actual') {
                            $qty = isset($poDetail['quantity_matrix']) ? collect($poDetail['quantity_matrix'])
                                ->where('color_id', $color->id)
                                ->where('size_id', $size->id)
                                ->where('particular', PurchaseOrder::particulars[0])
                                ->first()['value'] : 0;
                        } else {
                            $qty = isset($poDetail['quantity_matrix']) ? collect($poDetail['quantity_matrix'])
                                ->where('color_id', $color->id)
                                ->where('size_id', $size->id)
                                ->where('particular', PurchaseOrder::particulars[1])
                                ->first()['value'] : 0;
                        }

                        $data['costing_multiplier'] = (float)$costing_multiplier;
                        $data['qty'] = (float)$qty;
                        $data['set_ratio'] = (float)$item_ratio;
                        $data['po_no'] = $purchaseOrder['po_no'];
                        $data['color_id'] = $color->id;
                        $data['color'] = $color->name;
                        $data['size_id'] = $size->id;
                        $data['size'] = $size->name;
                        $data['dia'] = (float)round($budgetBreakDown->getDiaAvg());
                        $data['dia_fin_type'] = '';
                        $data['item_size'] = '';
                        $data['finish_cons'] = (float)number_format((float)$budgetBreakDown->getConsAvg(), 4, '.', '');
                        $data['process_loss'] = (float)number_format((float)($budgetBreakDown->getProcessAvg() ? $budgetBreakDown->getProcessAvg() : 0), 4, '.', '');
                        $data['grey_cons'] = number_format((float)($data['finish_cons'] + (($data['process_loss'] / 100) * $data['finish_cons'])), 4, '.', '');
                        $data['rate'] = number_format((float)$budgetBreakDown->getRateAvg(), 4, '.', '');
                        $data['amount'] = number_format((float)($data['rate'] * $data['grey_cons']), 4, '.', '');
                        $data['pcs'] = (float)$pcs;
                        $data['total_qty'] = number_format((float)($data['qty'] / ($data['costing_multiplier'] * $data['set_ratio']) * $data['grey_cons']), 4, '.', '');
                        $data['total_amount'] = number_format((float)($data['total_qty'] * $data['rate']), 4, '.', '');
                        $data['remarks'] = '';
                        $data['contrast'] = null;
                        $data['contrast_colors'] = null;

                        array_push($breakdown, $data);
                    }
                }
            }
        }

        return $breakdown;
    }
}
