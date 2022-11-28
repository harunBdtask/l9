<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Services\VariableSettings\VariableService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class POItemColorSizeBreakdownService
{
    public static function fabric($request, $budgetId, $itemId): array
    {
        $budget = Budget::with([
            'order.purchaseOrders.poDetails' => function ($query) use ($itemId) {
                $query->where('garments_item_id', $itemId);
            },
        ])->findOrFail($budgetId);

        $purchaseOrders = self::getPurchaseOrders($budget);
        // Get Variable Setting
        $variableSetting = VariableService::getVariableSettings($budget->factory_id, $budget->buyer_id);
        if (isset($variableSetting->variables_details)) {
            $variable = collect($variableSetting->variables_details)->get('fabric_total_qty_calculation');
        } else {
            $variable = null;
        }

        // Designing the data
        $breakdown = [];
        $item_ratio = '';
        $costing_multiplier = $budget->costing_multiplier;
        $pcs = 1;

        $ratios = [];
        if (isset($budget->order->item_details)) {
            $ratios = collect($budget->order->item_details['details'])->pluck('item_ratio', 'item_id');
        }

        foreach ($purchaseOrders as $purchaseOrder) {
            foreach ($purchaseOrder['poDetails'] as $poDetail) {

                $colorTypes = collect($poDetail['color_types'])
                    ->where('garments_item_id', $poDetail['item']->id)
                    ->first();

                foreach ($poDetail['colors'] as $color) {

                    $colorWiseType = collect($colorTypes['color_types'] ?? [])
                        ->where('color_id', $color->id)
                        ->first();

                    foreach ($poDetail['sizes'] as $size) {
                        if ($variable == 'actual') {
                            $matrix = isset($poDetail['quantity_matrix']) ? collect($poDetail['quantity_matrix'])
                                ->where('color_id', $color->id)
                                ->where('size_id', $size->id)
                                ->where('particular', PurchaseOrder::particulars[0])
                                ->first() : null;
                        } else {
                            $matrix = isset($poDetail['quantity_matrix']) ? collect($poDetail['quantity_matrix'])
                                ->where('color_id', $color->id)
                                ->where('size_id', $size->id)
                                ->where('particular', PurchaseOrder::particulars[1])
                                ->first() : null;
                        }
                        $qty = $matrix ? $matrix['value'] : 0;
                        $itemId = $poDetail['item']->id;
                        $itemRatio = $ratios[$itemId] ?: 1;
                        $pcs = $itemRatio * $costing_multiplier;
                        $finisCons = (float)number_format((float)isset($request['cons_avg']) ? $request['cons_avg'] : 0, 4, '.', '');
                        $data['costing_multiplier'] = (float)$costing_multiplier;
                        $data['qty'] = (float)$qty;
                        $data['set_ratio'] = (float)$itemRatio;
                        $data['po_no'] = $purchaseOrder['po_no'];
                        $data['is_approved'] = $purchaseOrder['is_approved'];
                        $data['created_at'] = $purchaseOrder['created_at'];
                        $data['po_id'] = $purchaseOrder['id'];
                        $data['color_id'] = $color->id;
                        $data['color_type_id'] = $colorWiseType['color_type_id'] ?? '';
                        $data['color'] = $color->name;
                        $data['size_id'] = $size->id;
                        $data['size'] = $size->name;
                        $data['dia'] = (float)round($request['dia_avg'] ?? 0);
                        $data['dia_fin_type'] = '';
                        $data['item_size'] = '';
                        $data['finish_cons'] = $finisCons;
                        $data['process_loss'] = format($request['process_avg'] ?? 0);
                        $data['grey_cons'] = format($data['finish_cons'] + (($data['process_loss'] / 100) * $data['finish_cons']));
                        $data['rate'] = format($request['rate_avg'] ?? 0);
                        $data['amount'] = format($data['rate'] * $data['grey_cons']);
                        $data['pcs'] = (float)$pcs;
                        $data['total_qty'] = format($data['qty'] / ($data['costing_multiplier'] * $data['set_ratio']) * $data['grey_cons']);
                        $data['total_amount'] = format($data['total_qty'] * $data['rate']);
                        $data['remarks'] = '';
                        $data['contrast'] = null;
                        $data['contrast_colors'] = null;
                        array_push($breakdown, $data);
                    }
                }
            }
        }

        return collect($breakdown)->where('qty', '!=', 0)->values()->toArray();
    }

    public static function trims($budgetId, $request): array
    {
        $budget = Budget::with('order.purchaseOrders.poDetails.garmentItem', 'order.purchaseOrders.country')
            ->findOrFail($budgetId);


        $ratios = [];

        $costingMultiplier = $budget->costing_multiplier;

        if (isset($budget->order->item_details)) {
            $ratios = collect($budget->order->item_details['details'])->pluck('item_ratio', 'item_id');
        }
        $purchaseOrders = self::getPurchaseOrders($budget);

        $breakdown = [];
        $consGmts = $request['cons_gmts'] ?? 0;
        $rate = $request['rate'] ?? 0;
        $amount = $request['amount'] ?? 0;

        /*Truth and my lies right now are falling like the rain.... So let the river run*/

        foreach ($purchaseOrders as $purchaseOrder) {
            foreach ($purchaseOrder['poDetails'] as $poDetail) {
                foreach ($poDetail['colors'] as $color) {
                    foreach ($poDetail['sizes'] as $size) {
                        $quantity = 0;

                        if (isset($poDetail['quantity_matrix'])) {
                            $quantity = collect($poDetail['quantity_matrix'])
                                ->where('color_id', $color->id)
                                ->where('size_id', $size->id)
                                ->where('particular', PurchaseOrder::particulars[0])->sum('value');
                        }

                        $itemId = $poDetail['item']->id;
                        $itemRatio = $ratios[$itemId] ?: 1;
                        $pcs = $itemRatio * $costingMultiplier;
                        $total_quantity = $quantity / ($costingMultiplier * $itemRatio) * $consGmts;

                        $totalCons = $consGmts + ($request['ex_cons_percent'] * .01 * $consGmts);
                        array_push($breakdown, [
                            'country' => $poDetail['country']->name,
                            'country_id' => $poDetail['country']->id,
                            'po_no' => $purchaseOrder['po_no'],
                            'is_approved' => $purchaseOrder['is_approved'],
                            'color' => $color->name,
                            'color_id' => $color->id,
                            'size' => $size->name,
                            'size_id' => $size->id,
                            'qty' => $quantity,
                            'item' => $poDetail['item']->name,
                            'item_id' => $itemId,
                            'set_ratio' => $ratios[$itemId] ?: 1,
                            'pcs' => $pcs,
                            'cons_gmts' => $consGmts,
                            'total_cons' => format((float)$totalCons),
                            'ex_cons_percent' => $request['ex_cons_percent'],
                            'rate' => $rate,
                            'amount' => $amount,
                            'total_qty' => format((float)$total_quantity),
                            'total_amount' => format((float)$total_quantity * $rate),
                            'costing_multiplier' => $costingMultiplier,
                        ]);
                    }
                }
            }
        }

        return collect($breakdown)->where('qty', '!=', 0)->values()->toArray();
    }

    public static function embellishment($budgetId, $request): array
    {
        $budget = Budget::with('order.purchaseOrders.poDetails.garmentItem', 'order.purchaseOrders.country')
            ->findOrFail($budgetId);

        $ratios = [];

        $costingMultiplier = $budget->costing_multiplier;

        if (isset($budget->order->item_details)) {
            $ratios = collect($budget->order->item_details['details'])->pluck('item_ratio', 'item_id');
        }

        $purchaseOrders = self::getPurchaseOrders($budget);

        $breakdown = [];

        $consGmts = $request['cons_gmts'] ?? 0;
        $rate = $request['rate'] ?? 0;
        $amount = $request['amount'] ?? 0;

        foreach ($purchaseOrders as $purchaseOrder) {
            foreach ($purchaseOrder['poDetails'] as $poDetail) {
                foreach ($poDetail['colors'] as $color) {
                    foreach ($poDetail['sizes'] as $size) {
                        $quantity = 0;

                        if (isset($poDetail['quantity_matrix'])) {
                            $quantity = collect($poDetail['quantity_matrix'])
                                ->where('color_id', $color->id)
                                ->where('size_id', $size->id)
                                ->where('particular', PurchaseOrder::particulars[0])->sum('value');
                        }

                        $itemId = $poDetail['item']->id;
                        $itemRatio = $ratios[$itemId] ?: 1;
                        $pcs = $itemRatio * $costingMultiplier;
                        $total_quantity = $quantity / ($costingMultiplier * $itemRatio) * $consGmts;

                        array_push($breakdown, [
                            'country' => $poDetail['country']->name,
                            'country_id' => $poDetail['country']->id,
                            'po_no' => $purchaseOrder['po_no'],
                            'is_approved' => $purchaseOrder['is_approved'],
                            'color' => $color->name,
                            'color_id' => $color->id,
                            'size' => $size->name,
                            'size_id' => $size->id,
                            'qty' => $quantity,
                            'item' => $poDetail['item']->name,
                            'item_id' => $itemId,
                            'set_ratio' => $ratios[$itemId] ?: 1,
                            'pcs' => $pcs,
                            'cons_gmts' => $consGmts,
                            'total_cons' => $consGmts,
                            'rate' => $rate,
                            'amount' => $amount,
                            'total_qty' => format((float)$total_quantity),
                            'total_amount' => format((float)$total_quantity * $rate),
                            'costing_multiplier' => $costingMultiplier,
                        ]);
                    }
                }
            }
        }

        return collect($breakdown)->where('qty', '!=', 0)->values()->toArray();
    }

    public static function wash($budgetId, $request): array
    {
        $budget = Budget::with('order.purchaseOrders.poDetails.garmentItem', 'order.purchaseOrders.country')
            ->findOrFail($budgetId);

        $ratios = [];

        $costingMultiplier = $budget->costing_multiplier;

        if (isset($budget->order->item_details)) {
            $ratios = collect($budget->order->item_details['details'])->pluck('item_ratio', 'item_id');
        }

        $purchaseOrders = self::getPurchaseOrders($budget);

        $breakdown = [];

        $consGmts = $request['cons_gmts'] ?? 0;
        $rate = $request['rate'] ?? 0;
        $processLoss = $request['process_loss'] ?? 0;
        $amount = $request['amount'] ?? 0;

        foreach ($purchaseOrders as $purchaseOrder) {
            foreach ($purchaseOrder['poDetails'] as $poDetail) {
                foreach ($poDetail['colors'] as $color) {
                    foreach ($poDetail['sizes'] as $size) {
                        $quantity = 0;

                        if (isset($poDetail['quantity_matrix'])) {
                            $quantity = collect($poDetail['quantity_matrix'])
                                ->where('color_id', $color->id)
                                ->where('size_id', $size->id)
                                ->where('particular', PurchaseOrder::particulars[0])->sum('value');
                        }

                        $itemId = $poDetail['item']->id;
                        $itemRatio = $ratios[$itemId] ?: 1;
                        $pcs = $itemRatio * $costingMultiplier;
                        $total_quantity = $quantity / ($costingMultiplier * $itemRatio) * $consGmts;

                        $breakdown[] = [
                            'country' => $poDetail['country']->name,
                            'country_id' => $poDetail['country']->id,
                            'po_no' => $purchaseOrder['po_no'],
                            'is_approved' => $purchaseOrder['is_approved'],
                            'color' => $color->name,
                            'color_id' => $color->id,
                            'size' => $size->name,
                            'size_id' => $size->id,
                            'qty' => $quantity,
                            'item' => $poDetail['item']->name,
                            'item_id' => $itemId,
                            'set_ratio' => $ratios[$itemId] ?: 1,
                            'pcs' => $pcs,
                            'cons_gmts' => $consGmts,
                            'total_cons' => $consGmts,
                            'rate' => $rate,
                            'process_loss' => $processLoss,
                            'amount' => $amount,
                            'total_qty' => format((float)$total_quantity),
                            'total_amount' => format((float)$total_quantity * $rate),
                            'costing_multiplier' => $costingMultiplier,
                        ];
                    }
                }
            }
        }

        return collect($breakdown)->where('qty', '!=', 0)->values()->toArray();
    }

    /**
     * @param $colors
     * @return Collection
     */
    private static function getPurchaseOrderColors($colors): Collection
    {
        return collect($colors)->map(function ($value) {
            return Color::query()->where('id', $value)->first();
        });
    }

    /**
     * @param $sizes
     * @return Collection
     */
    private static function getPurchaseOrderSizes($sizes): Collection
    {
        return collect($sizes)->map(function ($value) {
            return Size::query()->where('id', $value)->first();
        });
    }

    /**
     * @param $budgetData
     * @return Collection
     */
    private static function getPurchaseOrders($budgetData): Collection
    {
        return collect($budgetData->order->purchaseOrders)->sortByDesc('id')->map(function ($po) {
            return [
                'id' => $po['id'],
                'po_no' => $po['po_no'],
                'is_approved' => $po['is_approved'],
                'created_at' => $po['created_at'],
                'poDetails' => $po->poDetails->map(function ($poDetail) use ($po) {

                    $colors = self::getPurchaseOrderColors($poDetail['colors']);
                    $sizes = self::getPurchaseOrderSizes($poDetail['sizes']);
                    return [
                        'country' => $po['country'],
                        'colors' => $colors,
                        'item' => $poDetail->garmentItem,
                        'sizes' => $sizes,
                        'quantity_matrix' => $poDetail['quantity_matrix'],
                        'color_types' => $poDetail['color_types'],
                    ];
                }),
            ];
        });
    }
}
