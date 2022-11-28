<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Country;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Brand;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\ItemGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;
use Symfony\Component\HttpFoundation\Response;

class FormsApiController extends Controller
{

    public function getConversionFactor($groupId): JsonResponse
    {
        $item = ItemGroup::query()->where('id', $groupId)->first();
        $response = [
            'conversion_factor' => empty($item->conv_factor) ? 1 : $item->conv_factor,
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    public function fetchCountries()
    {
        $countries = Country::orderBy('name')->get([
            'id',
            'name as text',
        ]);

        return response()->json($countries);
    }

    public function fetchSuppliers()
    {
    }

    public function fetchBrands()
    {
        $brands = Brand::orderBy('brand_name')->get([
            'id',
            'brand_name as text',
        ]);

        return response()->json($brands);
    }

    public function trimsItems()
    {
        $items = ItemGroup::with('item', 'consUOM')->whereHas('item', function (Builder $builder) {
            $builder->where('item_name', 'Accessories');
        })->get()->map(function ($item) {
            $consUOM = $item->consUOM->unit_of_measurement;

            return [
                'id' => $item->id,
                'text' => $item->item_group . ' (' . $consUOM . ')',
                'name' => $item->item_group,
                'uom' => $consUOM,
                'uom_id' => $item->consUOM->id

            ];
        });

        return response()->json($items);
    }

    public function fetchCostingData($budgetId)
    {
        $costing = $this->fetchCosting($budgetId);

        $budget = $this->fetchBudget($budgetId);

        $ratios = [];

        $costingMultiplier = $budget->costing_multiplier;

        if (isset($budget->order->item_details)) {
            $ratios = collect($budget->order->item_details['details'])->pluck('item_ratio', 'item_id');
        }

        $purchaseOrders = $budget->order->purchaseOrders->map(\Closure::fromCallable([$this, 'formatPurchaseOrders']));

        $data = [];

        if (isset($costing->details['details'])) {
            foreach ($costing->details['details'] as $detail) {
                $breakdown = [];
                $consGmts = (float)$detail['cons_gmts'];
                $rate = (float)$detail['rate'];
                $amount = (float)$detail['amount'];

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

                                ];
                            }
                        }
                    }
                }

                $calculation = $this->calculateSumAndAvg($breakdown);

                if (!array_key_exists('breakdown', $detail)) {
                    $data[] = array_merge($detail, [
                        'rate' => $calculation['rate_avg'],
                        'amount' => $calculation['amount_avg'],
                        'total_quantity' => $calculation['total_qty_sum'],
                        'total_amount' => $calculation['total_amount_sum'],
                        'cons_gmts' => $calculation['total_cons_avg'],
                    ], ['breakdown' => array_merge(
                        ['details' => $breakdown],
                        $calculation
                    )]);
                } else {
                    $data[] = $detail;
                }
            }
        } else {
            $data[] = [];
        }


        return response()->json($data ?? []);
    }

    private function fetchCosting($budgetId)
    {
        return BudgetCostingDetails::where([
            'budget_id' => $budgetId,
            'type' => 'trims_costing',
        ])->first();
    }

    private function fetchBudget($budgetId)
    {
        return Budget::with('order.purchaseOrders.poDetails.garmentItem', 'order.purchaseOrders.country')->findOrFail($budgetId);
    }

    private function formatPurchaseOrders($po): array
    {
        return [
            'id' => $po['id'],
            'po_no' => $po['po_no'],
            'poDetails' => $po->poDetails->map(function ($poDetail) use ($po) {
                return [
                    "country" => $po['country'],
                    'item' => $poDetail->garmentItem,
                    'colors' => Color::whereIn('id', $poDetail['colors'])->get(),
                    'sizes' => Size::whereIn('id', $poDetail['sizes'])->get(),
                    'quantity_matrix' => $poDetail['quantity_matrix'],
                ];
            }),
        ];
    }

    private function calculateSumAndAvg($breakdown): array
    {
        $breakdownCollections = collect($breakdown);

        $calculation['pcs_sum'] = format($breakdownCollections->sum('pcs'));
        $calculation['pcs_avg'] = format($breakdownCollections->avg('pcs'));
        $calculation['rate_sum'] = format($breakdownCollections->sum('rate'));
        $calculation['rate_avg'] = format($breakdownCollections->avg('rate'));
        $calculation['amount_sum'] = format($breakdownCollections->sum('amount'));
        $calculation['amount_avg'] = format($breakdownCollections->avg('amount'));
        $calculation['total_qty_sum'] = format($breakdownCollections->sum('total_qty'));
        $calculation['total_qty_avg'] = format($breakdownCollections->avg('total_qty'));
        $calculation['total_cons_avg'] = format($breakdownCollections->avg('total_cons'));
        $calculation['total_cons_sum'] = format($breakdownCollections->sum('total_cons'));
        $calculation['total_amount_sum'] = format($breakdownCollections->sum('total_amount'));
        $calculation['total_amount_avg'] = format($breakdownCollections->avg('total_amount'));

        return $calculation;
    }

    public function consumptionUoms()
    {
        $uoms = UnitOfMeasurement::all([
            'id',
            'unit_of_measurement as text',
        ]);

        return response()->json($uoms);
    }

    public function fetchFactories(): JsonResponse
    {
        $factories = Factory::query()->userWiseFactories()->get([
            'id',
            'factory_name as text',
            'factory_address as location',
        ]);

        return response()->json($factories);
    }

    public function fetchBuyers()
    {
        $buyers = Buyer::query()
            ->permittedBuyer()
            ->get([
                'id',
                'name as text',
            ]);

        return response()->json($buyers);
    }


    public function suppliersForBooking(): JsonResponse
    {
        $accessories = Supplier::ACCESSORIES;
        $trims = Supplier::TRIMS;
        $suppliers = Supplier::query()
            ->where('party_type', 'LIKE', "%{$accessories}%")
            ->orWhere('party_type', 'LIKE', "%{$trims}%")
            ->withoutGlobalScopes()
            ->filterWithAssociateFactory('supplierWiseFactories', factoryId())
            ->get([
                'id as value',
                'name as text',
            ]);

        return response()->json($suppliers);
    }

    public function defaultCountry()
    {
        return Country::query()
                ->whereRaw("UPPER(name) LIKE '%BANGLADESH%'")->first() ?? null;
    }
}
