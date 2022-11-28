<?php


namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget;

use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinancialParameterSetup;
use SkylarkSoft\GoRMG\SystemSettings\Models\YarnCount;

class BudgetReportService
{
    const BREAKDOWN_DETAILS = 'breakdown.details';
    private static $budget;

    public static function getBudgetById($id): BudgetReportService
    {
        self::$budget = Budget::with(['buyer:id,name', 'order.purchaseOrders', 'order.purchaseOrders.poDetails', 'washCosting'])
            ->findOrFail($id);
        return new self;
    }

    public static function getBudgetByJobNo($jobNo): BudgetReportService
    {
        self::$budget = Budget::with(['buyer:id,name', 'order.purchaseOrders', 'order.purchaseOrders.poDetails', 'washCosting'])
            ->where('job_no', $jobNo)->first();
        return new self;
    }

    public static function budgetData()
    {
        $budget = self::$budget;
        if (!$budget) {
            return false;
        }

        $budget['knit_fabrics'] = $budget->knitFabric();
        $budget['woven_fabrics'] = $budget->wovenFabric();
        $yarnCollection = collect($budget->yarnDetails());
        $yarnCountsArr = $yarnCollection->pluck('count');
        $yarnCounts = YarnCount::query()->whereIn('id', $yarnCountsArr)->get()->keyBy('id');
        $budget['yarn_costing'] = $yarnCollection->map(function ($yarn) use ($budget, $yarnCounts) {
            $yarn['count_value'] = $yarnCounts[$yarn['count']]['yarn_count'] ?? '';
            $knitFabric = collect($budget->knitFabric())
                ->where('fabric_composition_id', $yarn['fabric_composition_id'])
                ->where('body_part_value', $yarn['body_part'])
                ->first();
            $totalYarnQty = $knitFabric['grey_cons_total_quantity'] * 0.01 * $yarn['percentage'];
            $yarn['total_yarn_qty'] = $totalYarnQty;
            $yarn['total_yarn_amount'] = $totalYarnQty * ($yarn['rate'] ?? 0);
            return $yarn;
        });
        $budget['conversion_costing'] = $budget->conversionDetails()->whereNull('chargeUnitForm')->values();
        $budget['conversion_costing_color_wise'] = $budget->conversionDetails()->whereNotNull('chargeUnitForm')->values();
        $budget['trims_details'] = $budget->trimDetails();
        $budget['embellishment_details'] = $budget->embellishmentDetails();
        $budget['commercial_details'] = $budget->commercialDetails();
        $budget['commission_details'] = $budget->commissionDetails();
        $budget['wash_details'] = $budget->washDetails();
        $budget['uom'] = BudgetService::UOM;
        $budget['costingPer'] = PriceQuotation::COSTING_PER;
        $budget['styleUom'] = PriceQuotation::STYLE_UOM;

        $budgetKnitFabric = $budget->knitFabric()->union($budget->wovenFabric())->values();

        $budget['conversion_costing'] = $budget['conversion_costing']->map(function ($item) use ($budgetKnitFabric) {
            $body_part_value = explode(', ', $item['fabric_description'], 2)[0];
            $fabric_composition_value = explode(', ', $item['fabric_description'], 2)[1];
            $knitFabric = $budgetKnitFabric->where('body_part_value', $body_part_value)->where('fabric_composition_value', $fabric_composition_value)->values();

            return [
                'fabric_description' => $item['fabric_description'],
                'process' => $item['process'],
                'req_qty' => $item['req_qty'],
                'unit' => $item['unit'] ?? 0,
                'amount' => $item['amount'],
                'total_qty' => $knitFabric->sum('grey_cons_total_quantity') ?? 0,
                'total_amount' => $knitFabric->sum('grey_cons_total_quantity') * $item['unit'],
            ];
        });

        $budget['conversion_costing_color_wise'] = $budget['conversion_costing_color_wise']->map(function ($item) use ($budgetKnitFabric) {
            $body_part_value = explode(', ', $item['fabric_description'], 2)[0];
            $fabric_composition_value = explode(', ', $item['fabric_description'], 2)[1];
            $knitFabric = $budgetKnitFabric->where('body_part_value', $body_part_value)->where('fabric_composition_value', $fabric_composition_value)->values()->pluck('greyConsForm.details')->flatten(1);

            return collect($item['chargeUnitForm']['details'])->map(function ($val) use ($knitFabric, $item) {
                return [
                    'process' => $item['process'],
                    'particulars' => $item['fabric_description'],
                    'gmts_color' => $val['color'],
                    'fabric_color' => $val['fabric_color'] ?? null,
                    'cons' => $val['grey_cons'],
                    'charge_unit' => $val['charge_unit'] ?? 0,
                    'total_qty' => $knitFabric->where('color_id', $val['color_id'])->sum('total_qty') ?? 0,
                    'total_amount' => $knitFabric->where('color_id', $val['color_id'])->sum('total_qty') * $val['charge_unit'],
                ];
            });
        })->flatten(1)->groupby('particulars');

        $budget['style_qty'] = $budget->order->pq_qty_sum;

        $plan_cut_qty_details = collect(collect($budget->order->purchaseOrders)->pluck('poDetails')->flatten(1))->pluck('quantity_matrix');
        $plan_cut_qty_details = $plan_cut_qty_details->map(function ($item) {
            return collect($item)->chunk(6);
        })->flatten(1);

        $budget['plan_cut_qty'] = $plan_cut_qty_details->collapse()
            ->where('particular', PurchaseOrder::PLAN_CUT_QTY)
            ->sum('value');

        $budget['po_no'] = collect($budget->order->purchaseOrders)->pluck('po_no')->implode(', ');
        $budget['financial_parameter'] = FinancialParameterSetup::where('date_from', '<=', $budget->costing_date)->where('date_to', '>=', $budget->costing_date)->first()->cost_per_minute ?? null;

        $budget['costing_per_pcs'] = isset($budget->costing['price_per_pcs']) ? (float)$budget->costing['price_per_pcs']['budgeted_cost'] : 0;
        $budget['gross_fob_value'] = isset($budget->costing['price_per_dzn']) ? (float)($budget->costing['price_per_dzn']['budgeted_cost']) : 0;
        $budget['value_gross_fob_value'] = (float)$budget['style_qty'] * (float)$budget['costing_per_pcs'];
        $budget['percent_gross_fob_value'] = isset($budget->costing['price_per_dzn']) ? (float)($budget->costing['price_per_dzn']['percent_price']) : 0;

        $budget['less_commission'] = isset($budget->costing['commission']) ? (float)$budget->costing['commission']['budgeted_cost'] : 0;
        $budget['value_less_commission'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['less_commission']);
        $budget['percent_less_commission'] = self::calculatePercentValue($budget['less_commission'], $budget['gross_fob_value']);

        $budget['net_fob_value'] = (float)($budget['gross_fob_value'] - $budget['less_commission']) ?? '';
        $budget['value_net_fob_value'] = (float)($budget['value_gross_fob_value'] - $budget['value_less_commission']);
        $budget['percent_net_fob_value'] = self::calculatePercentValue($budget['net_fob_value'], $budget['gross_fob_value']);

        $fabricCalculation = count($budget->fabricCalculation()) > 0 ? $budget->fabricCalculation()[0] : null;
        $yarnCost = isset($fabricCalculation['yarn_costing']) ? (float)$fabricCalculation['yarn_costing']['yarn_amount_sum'] : 0;
        $fabConId = $budget->yarnDetails()->pluck('fabric_composition_id')->unique();
        $knitFabricWithYardCosting = $budget['knit_fabrics']->values()->whereIn('fabric_composition_id', $fabConId)->values();
        $budget['yarn_cost'] = isset($yarnCost) ? $yarnCost : null;
        $budget['value_yarn_cost'] = collect($budget['yarn_costing']->map(function ($item) use ($knitFabricWithYardCosting) {
            $totalFabricAmount = ((collect($knitFabricWithYardCosting))->firstWhere('fabric_composition_id', $item['fabric_composition_id'])['grey_cons_total_quantity']) ?? 0;
            $percent = (float)$item['percentage'] ?? 0;
            $rate = (float)$item['rate'] ?? 0;

            return $totalFabricAmount * 0.01 * $percent * $rate;
        }))->reduce(function ($carry, $item) {
            return ($carry + $item);
        });
        $budget['percent_yarn_cost'] = self::calculatePercentValue($budget['yarn_cost'], $budget['gross_fob_value']);

        $conversionCost = isset($fabricCalculation['conversion_costing']) ? (float)$fabricCalculation['conversion_costing']['conversion_amount_sum'] : 0;
        $budget['conversion_cost'] = isset($conversionCost) ? $conversionCost : null;
        $budget['value_conversion_cost'] = collect($budget['conversion_costing_color_wise']->values())->flatten(1)->sum('total_amount') + $budget['conversion_costing']->sum('total_amount');
        $budget['percent_conversion_cost'] = self::calculatePercentValue($budget['conversion_cost'], $budget['gross_fob_value']);

        $budget['trim_cost'] = isset($budget->costing['trims_cost']) ? (float)$budget->costing['trims_cost']['budgeted_cost'] : 0;
        $budget['value_trim_cost'] = $budget['trims_details']->pluck(self::BREAKDOWN_DETAILS)->flatten(1)->sum('total_amount');
        $budget['percent_trim_cost'] = self::calculatePercentValue($budget['trim_cost'], $budget['gross_fob_value']);

        $budget['embellishment_cost'] = isset($budget->costing['embel_cost']) ? (float)$budget->costing['embel_cost']['budgeted_cost'] : 0;
        $budget['value_embellishment_cost'] = $budget['embellishment_details']->pluck(self::BREAKDOWN_DETAILS)->flatten(1)->sum('total_amount');
        $budget['percent_embellishment_cost'] = self::calculatePercentValue($budget['embellishment_cost'], $budget['gross_fob_value']);

        $budget['lab_test'] = isset($budget->costing['lab_test']) ? (float)$budget->costing['lab_test']['budgeted_cost'] : 0;
        $budget['value_lab_test'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['lab_test']);
        $budget['percent_lab_test'] = self::calculatePercentValue($budget['lab_test'], $budget['gross_fob_value']);

        $budget['inspection'] = isset($budget->costing['inspection']) ? (float)$budget->costing['inspection']['budgeted_cost'] : 0;
        $budget['value_inspection'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['inspection']);
        $budget['percent_inspection'] = self::calculatePercentValue($budget['inspection'], $budget['gross_fob_value']);

        $budget['freight'] = isset($budget->costing['freight']) ? (float)$budget->costing['freight']['budgeted_cost'] : 0;
        $budget['value_freight'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['freight']);
        $budget['percent_freight'] = self::calculatePercentValue($budget['freight'], $budget['gross_fob_value']);

        $budget['courier_cost'] = isset($budget->costing['courier_cost']) ? (float)$budget->costing['courier_cost']['budgeted_cost'] : 0;
        $budget['value_courier_cost'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['courier_cost']);
        $budget['percent_courier_cost'] = self::calculatePercentValue($budget['courier_cost'], $budget['gross_fob_value']);

        $budget['certificate_cost'] = isset($budget->costing['certificate_cost']) ? (float)$budget->costing['certificate_cost']['budgeted_cost'] : 0;
        $budget['value_certificate_cost'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['certificate_cost']);
        $budget['percent_certificate_cost'] = self::calculatePercentValue($budget['certificate_cost'], $budget['gross_fob_value']);

        $budget['gmts_wash_cost'] = isset($budget->costing['gmts_wash_cost']) ? (float)$budget->costing['gmts_wash_cost']['budgeted_cost'] : 0;
        $budget['value_gmts_wash_cost'] = $budget['wash_details']->pluck(self::BREAKDOWN_DETAILS)->flatten(1)->sum('total_amount');
        $budget['percent_gmts_wash_cost'] = self::calculatePercentValue($budget['gmts_wash_cost'], $budget['gross_fob_value']);

        $budget['other_direct_expenses'] = (float)$budget['lab_test'] + (float)$budget['inspection'] + (float)$budget['freight'] + (float)$budget['courier_cost'] + (float)$budget['certificate_cost'] + (float)$budget['gmts_wash_cost'];
        $budget['value_other_direct_expenses'] = (float)$budget['value_lab_test'] + (float)$budget['value_inspection'] + (float)$budget['value_freight'] + (float)$budget['value_courier_cost'] + (float)$budget['value_certificate_cost'] + (float)$budget['value_gmts_wash_cost'];
        $budget['percent_other_direct_expenses'] = self::calculatePercentValue($budget['other_direct_expenses'], $budget['gross_fob_value']);

        $budget['cost_of_material_services'] = (float)$budget['yarn_cost'] + (float)$budget['conversion_cost'] + (float)$budget['trim_cost'] + (float)$budget['embellishment_cost'] + (float)$budget['other_direct_expenses'];
        $budget['value_cost_of_material_services'] = (float)$budget['value_yarn_cost'] + $budget['value_conversion_cost'] + $budget['value_trim_cost'] + (float)$budget['value_embellishment_cost'] + (float)$budget['value_other_direct_expenses'];
        $budget['percent_cost_of_material_services'] = self::calculatePercentValue($budget['cost_of_material_services'], $budget['gross_fob_value']);

        $budget['contributions_value_additions'] = (float)$budget['net_fob_value'] - $budget['cost_of_material_services'];
        $budget['value_contributions_value_additions'] = $budget['value_net_fob_value'] - (float)$budget['value_cost_of_material_services'];
        $budget['percent_contributions_value_additions'] = self::calculatePercentValue($budget['contributions_value_additions'], $budget['gross_fob_value']);

        $budget['less_cm_cost'] = isset($budget->costing['cm_cost']) ? (float)$budget->costing['cm_cost']['budgeted_cost'] : 0;
        $budget['value_less_cm_cost'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['less_cm_cost']);
        $budget['percent_less_cm_cost'] = self::calculatePercentValue($budget['less_cm_cost'], $budget['gross_fob_value']);

        $budget['gross_profit'] = $budget['contributions_value_additions'] - (float)$budget['less_cm_cost'];
        $budget['value_gross_profit'] = (float)$budget['value_contributions_value_additions'] - (float)$budget['value_less_cm_cost'];
        $budget['percent_gross_profit'] = self::calculatePercentValue($budget['gross_profit'], $budget['gross_fob_value']);

        $budget['less_commercial_cost'] = isset($budget->costing['comml_cost']) ? (float)$budget->costing['comml_cost']['budgeted_cost'] : 0;
        $budget['value_less_commercial_cost'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['less_commercial_cost']);
        $budget['percent_less_commercial_cost'] = self::calculatePercentValue($budget['less_commercial_cost'], $budget['gross_fob_value']);

        $budget['less_operating_expenses'] = isset($budget->costing['openrt_exp']) ? (float)$budget->costing['openrt_exp']['budgeted_cost'] : 0;
        $budget['value_less_operating_expenses'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['less_operating_expenses']);
        $budget['percent_less_operating_expenses'] = self::calculatePercentValue($budget['less_operating_expenses'], $budget['gross_fob_value']);

        $budget['operating_profit_loss'] = $budget['gross_profit'] - ($budget['less_commercial_cost'] + $budget['less_operating_expenses']);
        $budget['value_operating_profit_loss'] = $budget['value_gross_profit'] - ($budget['value_less_commercial_cost'] + $budget['value_less_operating_expenses']);
        $budget['percent_operating_profit_loss'] = self::calculatePercentValue($budget['operating_profit_loss'], $budget['gross_fob_value']);

        $budget['net_profit'] = (float)$budget['operating_profit_loss'] - ((float)$budget['less_commercial_cost'] + (float)$budget['less_operating_expenses']);
        $budget['value_net_profit'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['net_profit']);
        $budget['percent_net_profit'] = self::getPercentValue($budget['style_qty'], $budget['value_net_profit']);

        $budget['depc_amort'] = isset($budget->costing['depc_amort']) ? (float)$budget->costing['depc_amort']['budgeted_cost'] : 0;
        $budget['value_depc_amort'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['depc_amort']);
        $budget['percent_depc_amort'] = self::calculatePercentValue($budget['depc_amort'], $budget['gross_fob_value']);

        $budget['interest'] = isset($budget->costing['interest']) ? (float)$budget->costing['interest']['budgeted_cost'] : 0;
        $budget['value_interest'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['interest']);
        $budget['percent_interest'] = self::calculatePercentValue($budget['interest'], $budget['gross_fob_value']);

        $budget['income_tax'] = isset($budget->costing['income_tax']) ? (float)$budget->costing['income_tax']['budgeted_cost'] : 0;
        $budget['value_income_tax'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['income_tax']);
        $budget['percent_income_tax'] = self::calculatePercentValue($budget['income_tax'], $budget['gross_fob_value']);

        $budget['totalGreyConsFabric'] = $totalGreyConsFabric = count($budget['knit_fabrics']) > 0 ? collect($budget['knit_fabrics'])->sum('grey_cons_amount') : 0;
        $budget['totalGreyConsAmount'] = $totalGreyConsAmount = count($budget['knit_fabrics']) ? collect($budget['knit_fabrics'])->sum('grey_cons_total_amount') : 0;
        $budget['percent_grey_cons_value'] = self::calculatePercentValue($budget['totalGreyConsFabric'], $budget['gross_fob_value']);

        $budget['net_profit_value'] = ($budget['operating_profit_loss'] - ($budget['interest'] + $budget['income_tax'] + $budget['depc_amort'])) - $totalGreyConsFabric;
        $budget['value_net_profit_value'] = ($budget['value_operating_profit_loss'] - ($budget['value_interest'] + $budget['value_income_tax'] + $budget['value_depc_amort'])) - $totalGreyConsAmount;
        $budget['percent_net_profit_value'] = self::calculatePercentValue($budget['value_net_profit_value'], $budget['value_gross_fob_value']);


        //        uom 1 for kg
        $knitFabricConsDetails = collect($budget['knit_fabrics'])->where('uom', '1')->values()->pluck('greyConsForm.calculation');
        $budget['knit_fabric_cons'] = ($knitFabricConsDetails)->sum('finish_cons_avg');
        $budget['value_knit_fabric_cons'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['knit_fabric_cons']);
        $budget['percent_knit_fabric_cons'] = self::getPercentValue($budget['style_qty'], $budget['value_knit_fabric_cons']);

        $wovenFabricCons = collect($budget['woven_fabrics'])->pluck('greyConsForm.calculation');
        $budget['woven_fabric_cons'] = $wovenFabricCons->sum('finish_cons_avg');
        $budget['value_woven_fabric_cons'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['woven_fabric_cons']);
        $budget['percent_woven_fabric_cons'] = self::getPercentValue($budget['style_qty'], $budget['value_woven_fabric_cons']);


        $budget['knit_fabric_fin_cons'] = $knitFabricConsDetails->sum('grey_cons_avg');
        $budget['value_knit_fabric_fin_cons'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['knit_fabric_fin_cons']);
        $budget['percent_knit_fabric_fin_cons'] = self::getPercentValue($budget['style_qty'], $budget['value_knit_fabric_fin_cons']);

        $budget['woven_fabric_fin_cons'] = $wovenFabricCons->sum('grey_cons_avg');
        $budget['value_woven_fabric_fin_cons'] = self::getBudgetValue($budget['style_qty'], $budget->costing_multiplier, $budget['woven_fabric_fin_cons']);
        $budget['percent_woven_fabric_fin_cons'] = self::getPercentValue($budget['style_qty'], $budget['value_woven_fabric_fin_cons']);

        $budget_items = $budget->items();
        $budget['items'] = collect($budget_items)->pluck('item_name')->implode(',');
        $budget['smv'] = collect($budget_items)->pluck('item_smv')->implode(',');
        $budget['gsm'] = $budget->fabricDetails()->pluck('gsm')->unique()->implode(',');
        $budget['shipment_date'] = collect($budget->order->purchaseOrders)->pluck('ex_factory_date')->max();
        $budget['avg_yarn_req'] = $budget->yarnDetails()->sum('cons_qty');
        return $budget;
    }

    private static function getBudgetValue($styleQty, $multiplier, $value = 0)
    {
        return $styleQty / $multiplier * $value;
    }

    private static function getPercentValue($total, $target)
    {
        return $total != 0 ? (($target / $total) * 100) : 0;
    }

    private static function calculatePercentValue($amount, $fobValue)
    {
        return $fobValue != 0 ? (($amount * 100) / $fobValue) : 0;
    }

    public static function budgetReportView($id)
    {
        $budget = Budget::with('buyer')->where('id', $id)->firstOrFail();

        return [
            'budget' => $budget ?? null,
            'budgetTrimDetails' => $budget->trimDetails() ?? null,
            'budgetFabricDetails' => $budget->fabricDetails() ?? null,
        ];
    }
}
