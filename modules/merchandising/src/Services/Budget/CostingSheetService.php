<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinancialParameterSetup;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;
use function PHPUnit\Framework\isEmpty;

class CostingSheetService
{
    private static $revenue;
    private static $totalQty;

    public static function budgetData($id, $type)
    {
        $budget = Budget::query()->with('currency','order')->find($id);
        $data['mainPartData'] = $budget ? self::formatMainPartData($budget) : null;
        $data['yarnCostData'] = $budget ? self::formatYarnCostData($budget) : [];
        $data['knitCostData'] = $budget ? self::formatKnitCostData($budget) : [];
        $data['dyingCostData'] = $budget ? self::formatDyingCostData($budget) : [];
        $data['trimsCostData'] = $budget ? self::formatTrimsCostData($budget) : [];
        $data['embellishmentCost'] = $budget ? self::formatEmbellishmentCostData($budget) : [];
        $data['commercialCost'] = $budget ? self::formatCommercialCostData($budget) : [];
        $data['commissionCost'] = $budget ? self::formatCommissionCostData($budget) : [];
        $data['othersCost'] = $budget ? self::formatLabCostData($budget) : [];
        $data['otherProcessCost'] = $budget ? self::formatOtherProcessCostData($budget) : [];
        $data['type'] = $type;
        $data['currency'] = $budget ? $budget->currency->currency_name : null;
        $data['orderCurrency'] = $budget ? $budget->order->currency->currency_name : null;
        return $data;
    }

    public static function formatMainPartData($budget)
    {
        $budgetData = $budget->load('buyer:id,name', 'order.PurchaseOrders.poDetails', 'productDepartment:id,product_department');
        $data['costing_date'] = $budgetData->costing_date ?? '';
        $data['id'] = $budgetData->id;
        $data['unique_id'] = $budgetData->job_no;
        $data['style_name'] = $budgetData->style_name ?? '';
        $data['image'] = $budgetData->image ?? '';
        $data['buyer_id'] = $budgetData->buyer_id ?? '';
        $data['buyer_name'] = $budgetData->buyer->name ?? '';
        $data['product_department'] = $budgetData->productDepartment->product_department ?? '';
        $data['order_qty'] = collect($budgetData->order->PurchaseOrders)->sum('po_quantity') ?? 0;
//        $data['fob_price'] = isset($budgetData->costing['price_per_pcs']) ? $budgetData->costing['price_per_pcs']['budgeted_cost'] : 0;
        $data['fob_price'] = collect($budget->order->PurchaseOrders)->average('avg_rate_pc_set');
        $data['uom'] = $budgetData->order_uom_id == 1 ? 'PCS' : ($budgetData->order_uom_id == 2 ? 'SET' : '');
//        $data['revenue'] = (float)$data['order_qty'] * (float)$data['fob_price'];
        $data['revenue'] = collect($budget->order->PurchaseOrders)->map(function ($item) {
            $rate = $item['avg_rate_pc_set'] ?? 0;
            $qty = $item['po_quantity'] ?? 0;
            return $rate * $qty;
        })->sum();
//        return  collect($budgetData->order->PurchaseOrders)->pluck('poDetails')->flatten()
//            ->pluck('quantity_matrix')->collapse()->groupBy('color')->map(function ($item){
//                $chunkedItem =  collect($item)->whereIn('particular',['Rate', 'Qty.'])->values()->chunk(2);
//               return collect($chunkedItem)->map(function ($chunkedItem){
//                   $rate =  collect($chunkedItem)->where('particular', 'Rate')->first()['value'];
//                   $qty =  collect($chunkedItem)->where('particular', 'Qty.')->first()['value'];
//                   return $rate * $qty;
//                });
//            })->values()->collapse()->sum();

        $financialParams = $data['costing_date'] ? FinancialParameterSetup::query()
            ->where('date_from', '<=', Carbon::parse($data['costing_date'])->format('Y-m-d'))
            ->Where('date_to', '>=', Carbon::parse($data['costing_date'])->format('Y-m-d'))->first() : '';

        $data['cpm'] = $financialParams ? $financialParams['cost_per_minute'] : 0;
        $data['smv'] = $budgetData->order->smv;
        $data['machine_line'] = $budgetData->machine_line ?? 0;
        $data['sew_efficiency'] = $budgetData->sew_efficiency ?? 0;
        $data['cm_view_2'] = isset($budget->costing['cm_cost']) ? $budget->costing['cm_cost']['budgeted_cost'] : 0;
        $data['costing_mul'] = $budget->costing_multiplier;
        $data['costing_per'] = PriceQuotation::COSTING_PER[$budget->costing_per];

        self::$revenue = $data['revenue'];
        self::$totalQty = $data['order_qty'];
        return $data;
    }

    public static function formatYarnCostData($budget)
    {
//        1 => production , 2 => purchase
        $yarnPurchase = collect($budget->fabricDetails())->where('fabric_source', 2)->values();
        $yarnPurchase = count($yarnPurchase) > 0 ? self::formatYarnPurchaseData($yarnPurchase) : [];

        $yarnProduction = collect($budget->fabricDetails())->where('fabric_source', 1)->values();
        $yarnProduction = count($yarnProduction) > 0 ? self::formatYarnProductionData($budget, $yarnProduction) : [];

//        return $yarnProduction;
        return array_merge($yarnPurchase, $yarnProduction);
    }

    public static function formatYarnProductionData($budget, $yarnProduction)
    {
        return $budget->yarnDetails()->map(function ($item) use ($yarnProduction) {
//                return $item['fabric_composition_id'];
            $body_part_value = explode(', ', $item['fabric_description'])[0];
            $fabric_composition = explode(', ', $item['fabric_description'], 2)[1];

            $yarn = collect($yarnProduction)->where('body_part_value', $body_part_value)
                ->where('fabric_composition_value', $fabric_composition)
                ->where('fabric_composition_id', $item['fabric_composition_id'])->first();
            $uomId = array_get($yarn, 'uom');
            $rate = $item['rate'] ?? 0;
            $percentage = $item['percentage'] ?? 0;
            $yarnQty = $yarn['grey_cons_total_quantity'] ?? 0;
            $totalQty = $yarnQty * 0.01 * $percentage;
            $totalAmount = $totalQty * $rate;
//            yarn_count
            $counts = YarnCount::find(array_get($item, 'count'));
            $wastage = isset($yarn['greyConsForm']['calculation']) ? array_get($yarn['greyConsForm']['calculation'], 'process_loss_avg', 0) : 0;

            return [
                'supplier' => $yarn ? $yarn['supplier_value'] : '',
                'fabric_width' => $yarn ?
                    (isset($yarn['greyConsForm']['details']) ?
                        collect($yarn['greyConsForm']['details'])->pluck('dia')->unique()->join('/')
                        : '') : '',
                'rate' => $rate,
                'uom' => Budget::UOM[$uomId] ?? '',
                'total_qty' => $totalQty,
                'description' => $fabric_composition . ', ' . array_get($counts, 'yarn_count') . ', ' . array_get($item, 'yarn_composition_value') . ', ' . array_get($item, 'type'),
                'total_amount' => $totalAmount,
                'cons' => array_get($item, 'cons_qty'),
                'wastage' => $wastage,
                'pre_cost' => self::calculatePreCost($totalAmount)
            ];
        })->toArray();
//        return collect($yarnProduction)->map(function ($item) use ($budget) {
//            $body_part_value = $item['body_part_value'];
//            $fabric_composition_value = $item['fabric_composition_value'];
//            $fabric_description = $body_part_value . ', ' . $fabric_composition_value;
//            $yarnCost = collect($budget->yarnDetails())->where('fabric_description', $fabric_description);
//            $rate = self::yarnPurchaseRateCalculation($yarnCost);
//            $greyQty = $item['grey_cons_total_quantity'] ?? 0;
//            $amount = $rate * $greyQty;
//            return [
//                'supplier' => $item['supplier_value'] ?? '',
//                'fabric_width' => isset($item['greyConsForm']['details']) ? collect($item['greyConsForm']['details'])->pluck('dia')->unique()->join('/') : '',
//                'rate' => $rate,
//                'uom' => Budget::UOM[$item['uom']] ?? '',
//                'total_qty' => $item['grey_cons_total_quantity'] ?? 0,
//                'description' => $item['fabric_composition_value'] ?? '',
//                'total_amount' => $amount,
//                'cons' => isset($item['greyConsForm']['calculation']) ? collect($item['greyConsForm']['calculation'])['finish_cons_avg'] : 0,
//                'wastage' => isset($item['greyConsForm']['calculation']) ? collect($item['greyConsForm']['calculation'])['process_loss_avg'] : 0,
//                'pre_cost' => self::calculatePreCost($amount)
//            ];
//        })->toArray();
    }

    public static function formatYarnPurchaseData($yarnPurchase): array
    {
        return collect($yarnPurchase)->map(function ($item) {
            $total = $item['grey_cons_total_amount'] ?? 0;
            return [
                'supplier' => $item['supplier_value'] ?? '',
                'fabric_width' => isset($item['greyConsForm']['details']) ? collect($item['greyConsForm']['details'])->pluck('dia')->unique()->join('/') : '',
                'rate' => $item['grey_cons_rate'] ?? 0,
                'uom' => Budget::UOM[$item['uom']] ?? '',
                'total_qty' => $item['grey_cons_total_quantity'] ?? 0,
                'total_amount' => $total,
                'cons' => isset($item['greyConsForm']['calculation']) ? collect($item['greyConsForm']['calculation'])['finish_cons_avg'] : 0,
                'wastage' => isset($item['greyConsForm']['calculation']) ? collect($item['greyConsForm']['calculation'])['process_loss_avg'] : 0,
                'description' => $item['fabric_composition_value'] ?? '',
                'pre_cost' => self::calculatePreCost($total)

            ];
        })->toArray();
    }

    public static function formatKnitCostData($budget): Collection
    {
        $conversionDetails = $budget->conversionDetails()->where('process', 'Knitting');
        $fabricDetails = $budget->fabricDetails();
        return self::formatConversionCostData($conversionDetails, $fabricDetails);
    }

    public static function formatDyingCostData($budget): Collection
    {
        $conversionDetails = $budget->conversionDetails()->where('process', 'Dyeing');
        $fabricDetails = $budget->fabricDetails();
        return self::formatConversionCostData($conversionDetails, $fabricDetails);
    }

    public static function formatOtherProcessCostData($budget)
    {
        $conversionDetails = $budget->conversionDetails()->whereNotIn('process', ['Dyeing', 'Knitting']);
        $fabricDetails = $budget->fabricDetails();
        return self::formatConversionCostData($conversionDetails, $fabricDetails);
    }

    public static function formatTrimsCostData($budget)
    {
        return $budget->trimDetails()->map(function ($item) {
            return [
                'item' => $item,
                'group_name' => $item['group_name'] ?? '',
                'supplier' => $item['nominated_supplier_value'] ?? '',
                'rate' => $item['rate'] ?? 0,
                'cons' => $item['cons_gmts'] ?? 0,
                'total_cons' => $item['total_cons'] ?? 0,
                'total_qty' => $item['total_quantity'] ?? 0,
                'total_amount' => $item['total_amount'] ?? 0,
                'uom' => $item['cons_uom_value'] ?? '',
                'wastage' => isset($item['breakdown']['details']) ? collect($item['breakdown']['details'])->avg('ex_cons_percent') : 0,
                'pre_cost' => self::calculatePreCost($item['total_amount'] ?? 0),
            ];
        });
    }

    public static function formatConversionCostData($conversionDetails, $fabricDetails): Collection
    {
        return collect($conversionDetails)->map(function ($item) use ($fabricDetails) {
            $bodyPartValue = explode(', ', $item['fabric_description'], 2)[0];
            $fabric_composition_value = explode(', ', $item['fabric_description'], 2)[1];
            $fabric_name = explode(' [', $fabric_composition_value)[0];
            $knitFabric = $fabricDetails->where('body_part_value', $bodyPartValue)->where('fabric_composition_value', $fabric_composition_value)->first();

            $rate = $item['unit'] ?? 0;
            $qty = $knitFabric['grey_cons_total_quantity'] ?? 0;
            $amount = (float)$rate * (float)$qty;
            $knitUom = $knitFabric['uom'] ?? null;

            return [
                'supplier' => $knitFabric['supplier_value'] ?? '',
                'description' => $fabric_name,
                'rate' => $rate,
                'total_qty' => $qty,
                'total_amount' => $amount,
                'pre_cost' => self::calculatePreCost($amount),
                'uom' => array_key_exists($knitUom, Budget::UOM) ? Budget::UOM[$knitUom] : '',
                'process' => strtoupper($item['process']) ?? ''

            ];
        })->values();
    }

    public static function formatEmbellishmentCostData($budget)
    {
        $embl = $budget->embellishmentDetails();
        $emblDetails = $embl ? self::formatEmbelishmentData($embl) : [];
        $wash = $budget->washDetails();
        $washDetails = $wash ? self::formatEmbelishmentData($wash) : [];
        $data['embelishmentCostData'] = array_merge($emblDetails, $washDetails);
        $data['totalEmblCost'] = collect($data['embelishmentCostData'])->sum('total_amount');
        $data['pre_cost'] = self::calculatePreCost($data['totalEmblCost']);
        return $data;
    }

    public static function formatEmbelishmentData($embl)
    {
        return collect($embl)->map(function ($item) {
            $amount = $item['breakdown']['total_amount_sum'] ?? 0;
            return [
                'details' => array_get($item, 'name') . '-' . array_get($item, 'type'),
                'total_amount' => $amount,
                'rate' => $item['consumption_rate'] ?? 0,
                'pre_cost' => self::calculatePreCost($amount)
            ];
        })->toArray();
    }

    public static function formatCommissionCostData($budget): array
    {
        $commercialAmount = $budget->commissionDetails()->sum('amount') ?? 0;
        $data['totalCommissionAmount'] = self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $commercialAmount);
        $data['pre_cost'] = self::calculatePreCost($data['totalCommissionAmount']);
        return $data;
    }

    public static function formatCommercialCostData($budget): array
    {
        $commercialAmount = $budget->commercialDetails()->sum('amount') ?? 0;
        $data['totalCommercialAmount'] = self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $commercialAmount);
        $data['pre_cost'] = self::calculatePreCost($data['totalCommercialAmount']);
        return $data;
    }

    public static function formatLabCostData($budget): array
    {
//        return $budget->costing;
        $labCost = isset($budget->costing['lab_test']) ? $budget->costing['lab_test']['budgeted_cost'] : 0;
        $inspection = isset($budget->costing['inspection']) ? $budget->costing['inspection']['budgeted_cost'] : 0;
        $freight = isset($budget->costing['freight']) ? $budget->costing['freight']['budgeted_cost'] : 0;
        $courier_cost = isset($budget->costing['courier_cost']) ? $budget->costing['courier_cost']['budgeted_cost'] : 0;
        $certificate_cost = isset($budget->costing['certificate_cost']) ? $budget->costing['certificate_cost']['budgeted_cost'] : 0;
        $deffd_lc_cost = isset($budget->costing['deffd_lc_cost']) ? $budget->costing['deffd_lc_cost']['budgeted_cost'] : 0;
        $design_cost = isset($budget->costing['design_cost']) ? $budget->costing['design_cost']['budgeted_cost'] : 0;
        $studio_cost = isset($budget->costing['studio_cost']) ? $budget->costing['studio_cost']['budgeted_cost'] : 0;
        $openrt_exp = isset($budget->costing['openrt_exp']) ? $budget->costing['openrt_exp']['budgeted_cost'] : 0;
        $interest = isset($budget->costing['interest']) ? $budget->costing['interest']['budgeted_cost'] : 0;
        $income_tax = isset($budget->costing['income_tax']) ? $budget->costing['income_tax']['budgeted_cost'] : 0;
        $depc_amort = isset($budget->costing['depc_amort']) ? $budget->costing['depc_amort']['budgeted_cost'] : 0;
        $commission = isset($budget->costing['commission']) ? $budget->costing['commission']['budgeted_cost'] : 0;

        return [
            'Lab Cost' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $labCost),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $labCost))
            ],
            'Inspection Cost' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $inspection),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $inspection))
            ],
            'Freight Cost' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $freight),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $freight))
            ],
            'Courier Cost' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $courier_cost),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $courier_cost))
            ],
            'Certificate Cost' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $certificate_cost),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $certificate_cost))
            ],
            'Deffd Cost' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $deffd_lc_cost),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $deffd_lc_cost))
            ],
            'Design Cost' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $design_cost),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $design_cost))
            ],
            'Studio Cost' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $studio_cost),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $studio_cost))
            ],
            'Port Handling Cost' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $openrt_exp),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $openrt_exp))
            ],
            'Interest Amount' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $interest),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $interest))
            ],
            'Income Tax' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $income_tax),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $income_tax))
            ],
            'Depc Amount' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $depc_amort),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $depc_amort))
            ],
            'Commission Amount' => [
                'amount' => self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $commission),
                'pre_cost' => self::calculatePreCost(self::getBudgetValue(self::$totalQty, $budget->costing_multiplier, $commission))
            ],
        ];
    }

    public static function calculatePreCost($totalAmount)
    {
        return self::$revenue != 0 ? ((float)$totalAmount / (float)self::$revenue) * 100 : 0;
    }

    private static function getBudgetValue($styleQty, $multiplier, $value = 0)
    {
        return $value != 0 ? ($styleQty / $multiplier * $value) : 0;
    }

    public static function yarnPurchaseRateCalculation($yarnCost)
    {
        return collect($yarnCost)->map(function ($yarn) {
                return ($yarn['percentage'] * $yarn['rate']);
            })->sum() / 100;
    }
}
