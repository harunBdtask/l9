<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services;

use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBookingDetailsBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBookingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrderDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrderDetail;

class BudgetWiseWOReportService
{
    public $company, $buyer, $unique;

    public function __construct(Request $request)
    {
        $this->company = $request->get('company_id');
        $this->buyer = $request->get('buyer_id');
        $this->unique = $request->get('unique_id');
    }

    /**
     * @return array
     */
    public function report(): array
    {
        $budget = Budget::query()
            ->with('fabricCosting', 'trimCosting', 'embellishmentCosting', 'washCosting', 'commercialCosting')
            ->where('job_no', $this->unique)
            ->first();

        $order = Order::query()
            ->with('purchaseOrders')
            ->where('style_name', $budget['style_name'])
            ->first();

        $orderFobValue = $order->purchaseOrders->map(function ($poCollection) {
            return $poCollection->po_quantity * $poCollection->avg_rate_pc_set;
        })->sum();

        $budgetCosting = $budget->costing;

        $reportData['style'] = $budget['style_name'] ?? null;
        $reportData['buyer'] = $budget->buyer->name;
        $reportData['company'] = $budget->factory->factory_name;
        $reportData['total_qty'] = $order['pq_qty_sum'];
        $reportData['total_fob_value'] = $orderFobValue;
        $reportData['fabricPurchaseDetails'] = $this->fabricPurchaseDetails($budget);
        $reportData['fabricProductionDetails'] = $this->fabricProductionDetails($budget);
        $reportData['trimsDetails'] = $this->trimsDetails($budget);
        $reportData['otherDetails'] = $this->otherDetails($budget);
        $reportData['budgetCommercialCost'] = ($budget->costing['comml_cost']['budgeted_cost'] / 12) * $order['pq_qty_sum'];

        $reportData['budgetOthersCosting'] = $budgetCosting['cm_cost']['budgeted_cost']
            + $budgetCosting['freight']['budgeted_cost']
            + $budgetCosting['studio_cost']['budgeted_cost']
            + $budgetCosting['design_cost']['budgeted_cost']
            + $budgetCosting['lab_test']['budgeted_cost']
            + $budgetCosting['courier_cost']['budgeted_cost']
            + $budgetCosting['deffd_lc_cost']['budgeted_cost']
            + $budgetCosting['certificate_cost']['budgeted_cost'];

        $reportData['budgetOthersCosting'] = ($reportData['budgetOthersCosting'] / 12) * $order['pq_qty_sum'];


//        dd($reportData);
        return $reportData;
    }

    /**
     * @param $budget
     * @return array
     */
    public function fabricPurchaseDetails($budget): array
    {
        $fabricDetails = [];

        $fabricDetails['total_budget_qty'] = 0;
        $fabricDetails['total_budget_value'] = 0;
        $fabricDetails['total_booking_qty'] = 0;
        $fabricDetails['total_booking_value'] = 0;
        $fabricDetails['total_balance_qty'] = 0;
        $fabricDetails['total_balance_value'] = 0;

        if (isset($budget->fabricCosting['details']['details']) && count($budget->fabricCosting['details']['details'])) {
            $fabricBookingDetails = FabricBookingDetailsBreakdown::query()->where('job_no', $this->unique)->get();
            $shortFabricBookingDetails = ShortFabricBookingDetailsBreakdown::query()->where('job_no', $this->unique)->get();

            $fabricDetails['details'] = collect($budget->fabricCosting['details']['details']['fabricForm'])
                ->where('fabric_source_value', 'Purchase')
                ->map(function ($fabricBudgetData) use ($fabricBookingDetails, $shortFabricBookingDetails, &$fabricDetails) {
                    $fabricCompositionValue = explode('[', $fabricBudgetData['fabric_composition_value']);
                    $fabricConstruction = trim($fabricCompositionValue[0]);
                    $fabricComposition = trim($fabricCompositionValue[1], ']');

                    $fabricBookingData = collect($fabricBookingDetails)
                        ->where('body_part_id', $fabricBudgetData['body_part_id'])
                        ->where('composition', $fabricComposition)
                        ->where('construction', $fabricConstruction)
                        ->where('uom', $fabricBudgetData['uom'])
                        ->where('gsm', $fabricBudgetData['gsm'])
                        ->all();

                    $shortFabricBookingData = collect($shortFabricBookingDetails)
                        ->where('body_part_id', $fabricBudgetData['body_part_id'])
                        ->where('composition', $fabricComposition)
                        ->where('construction', $fabricConstruction)
                        ->where('uom', $fabricBudgetData['uom'])
                        ->where('gsm', $fabricBudgetData['gsm'])
                        ->all();

                    $fabric_budget_qty = $fabricBudgetData['grey_cons_total_quantity'] ?? 0;
                    $fabric_budget_value = $fabricBudgetData['grey_cons_total_amount'] ?? 0;
                    $fabric_booking_qty = (collect($fabricBookingData)->sum('actual_wo_qty') ?? 0) +
                        (collect($shortFabricBookingData)->sum('actual_wo_qty') ?? 0);
                    $fabric_booking_value = (collect($fabricBookingData)->sum('amount') ?? 0) +
                        (collect($shortFabricBookingData)->sum('amount') ?? 0);
                    $fabric_balance_qty = round($fabric_budget_qty) - round($fabric_booking_qty);
                    $fabric_balance_value = $fabric_budget_value - $fabric_booking_value;

                    $fabricDetails['total_budget_qty'] = ($fabricDetails['total_budget_qty'] ?? 0) + $fabric_budget_qty;
                    $fabricDetails['total_budget_value'] = ($fabricDetails['total_budget_value'] ?? 0) + $fabric_budget_value;
                    $fabricDetails['total_booking_qty'] = ($fabricDetails['total_booking_qty'] ?? 0) + $fabric_booking_qty;
                    $fabricDetails['total_booking_value'] = ($fabricDetails['total_booking_value'] ?? 0) + $fabric_booking_value;
                    $fabricDetails['total_balance_qty'] = ($fabricDetails['total_balance_qty'] ?? 0) + $fabric_balance_qty;
                    $fabricDetails['total_balance_value'] = ($fabricDetails['total_balance_value'] ?? 0) + $fabric_balance_value;

                    return [
                        "item" => $fabricBudgetData['body_part_value'] . " " . $fabricBudgetData['fabric_composition_value'],
                        "budget_qty" => round($fabric_budget_qty) . " " . collect($fabricBookingData)->pluck('uom_value')->first() ?? null,
                        "budget_value" => number_format($fabric_budget_value, 2),
                        "booking_qty" => round($fabric_booking_qty) . " " . collect($fabricBookingData)->pluck('uom_value')->first() ?? null,
                        "booking_value" => number_format($fabric_booking_value, 2),
                        "balance_qty" => round($fabric_balance_qty) . " " . collect($fabricBookingData)->pluck('uom_value')->first() ?? null,
                        "balance_value" => number_format($fabric_balance_value, 2),
                    ];
                });
        }
        return $fabricDetails;
    }

    /**
     * @param $budget
     * @return array
     */
    public function fabricProductionDetails($budget): array
    {
        $fabricDetails = [];

        $fabricDetails['total_budget_qty'] = 0;
        $fabricDetails['total_budget_value'] = 0;
        $fabricDetails['total_booking_qty'] = 0;
        $fabricDetails['total_booking_value'] = 0;
        $fabricDetails['total_balance_qty'] = 0;
        $fabricDetails['total_balance_value'] = 0;

        if (isset($budget->fabricCosting['details']['details']) && count($budget->fabricCosting['details']['details'])) {
            $yarnPurchaseOrderDetail = YarnPurchaseOrderDetail::query()
                ->with('unitOfMeasurement')
                ->where('unique_id', $this->unique)
                ->get();

            $fabricDetails['details'] = collect($budget->fabricCosting['details']['details']['yarnCostForm'])
                ->map(function ($yarnBudgetData) use ($yarnPurchaseOrderDetail, &$fabricDetails, $budget) {

                    $yarnPurchaseData = collect($yarnPurchaseOrderDetail)
                        ->where('yarn_composition_id', $yarnBudgetData['yarn_composition'])
//                        ->where('yarn_count_id', $yarnBudgetData['count'])
                        ->where('yarn_type', $yarnBudgetData['type'] ?? null)
                        ->all();

                    $fabricBudgetDataOfYarn = collect($budget->fabricCosting['details']['details']['fabricForm'])
                        ->where('fabric_source_value', 'Production')
                        ->where('body_part_value', $yarnBudgetData['body_part'] ?? null)
                        ->where('fabric_composition_id', $yarnBudgetData['fabric_composition_id'])
                        ->first();


                    $yarn_budget_qty = $fabricBudgetDataOfYarn['grey_cons_total_quantity'] ?? 0;
                    $yarn_budget_value = ($yarnBudgetData['rate'] * $yarn_budget_qty) ?? 0;

                    $yarnPurchaseQty = (collect($yarnPurchaseData)->sum('wo_qty') ?? 0);
                    $yarnPurchaseValue = (collect($yarnPurchaseData)->sum('amount') ?? 0);

                    $yarn_balance_qty = round($yarn_budget_qty) - round($yarnPurchaseQty);
                    $yarn_balance_value = $yarn_budget_value - $yarnPurchaseValue;

                    $fabricDetails['total_budget_qty'] = ($fabricDetails['total_budget_qty'] ?? 0) + $yarn_budget_qty;
                    $fabricDetails['total_budget_value'] = ($fabricDetails['total_budget_value'] ?? 0) + $yarn_budget_value;
                    $fabricDetails['total_booking_qty'] = ($fabricDetails['total_booking_qty'] ?? 0) + $yarnPurchaseQty;
                    $fabricDetails['total_booking_value'] = ($fabricDetails['total_booking_value'] ?? 0) + $yarnPurchaseValue;
                    $fabricDetails['total_balance_qty'] = ($fabricDetails['total_balance_qty'] ?? 0) + $yarn_balance_qty;
                    $fabricDetails['total_balance_value'] = ($fabricDetails['total_balance_value'] ?? 0) + $yarn_balance_value;

                    $uom = collect($yarnPurchaseOrderDetail)->first();

                    return [
                        "item" => $yarnBudgetData['yarn_composition_value'] . " - " . $yarnBudgetData['fabric_description'],
                        "budget_qty" => round($yarn_budget_qty) . " " . ($uom ? $uom->unitOfMeasurement->unit_of_measurement : null),
                        "budget_value" => number_format($yarn_budget_value, 2),
                        "booking_qty" => round($yarnPurchaseQty) . " " . ($uom ? $uom->unitOfMeasurement->unit_of_measurement : null),
                        "booking_value" => number_format($yarnPurchaseValue, 2),
                        "balance_qty" => round($yarn_balance_qty) . " " . ($uom ? $uom->unitOfMeasurement->unit_of_measurement : null),
                        "balance_value" => number_format($yarn_balance_value, 2),
                    ];
                });
        }
        return $fabricDetails;
    }

    /**
     * @param $budget
     * @return array
     */
    public function trimsDetails($budget): array
    {
        $trimsDetails = [];

        $trimsDetails['total_budget_qty'] = 0;
        $trimsDetails['total_budget_value'] = 0;
        $trimsDetails['total_booking_qty'] = 0;
        $trimsDetails['total_booking_value'] = 0;
        $trimsDetails['total_balance_qty'] = 0;
        $trimsDetails['total_balance_value'] = 0;

        if (isset($budget->trimCosting['details']['details']) && count($budget->trimCosting['details']['details'])) {

            $trimsBookingDetails = TrimsBookingDetails::query()
                ->where('budget_unique_id', $this->unique)
                ->get();

            $shortTrimsBookingDetails = ShortTrimsBookingDetails::query()
                ->where('budget_unique_id', $this->unique)
                ->get();

            $trimsDetails['details'] = collect($budget->trimCosting['details']['details'])
                ->map(function ($trimsBudgetData) use ($trimsBookingDetails, $shortTrimsBookingDetails, &$trimsDetails) {

                    $trimsBookingData = collect($trimsBookingDetails)
                        ->where('item_id', $trimsBudgetData['group_id'])
                        ->when(isset($trimsBudgetData['cons_uom_id']), function ($query) use ($trimsBudgetData) {
                            return $query->where('cons_uom_id', $trimsBudgetData['cons_uom_id']);
                        })
                        ->all();

                    $shortTrimsBookingData = collect($shortTrimsBookingDetails)
                        ->where('item_id', $trimsBudgetData['group_id'])
                        ->when(isset($trimsBudgetData['cons_uom_id']), function ($query) use ($trimsBudgetData) {
                            return $query->where('cons_uom_id', $trimsBudgetData['cons_uom_id']);
                        })
                        ->all();


                    $trims_budget_qty = round(str_replace(',', '', ($trimsBudgetData['total_quantity'] ?? 0))) ?? 0;
                    $trims_budget_value = (double)($trimsBudgetData['total_amount'] ?? 0);

                    $trims_booking_qty = (collect($trimsBookingData)
                                ->sum('work_order_qty') ?? 0) +
                        (collect($shortTrimsBookingData)
                                ->sum('work_order_qty') ?? 0);

                    $trims_booking_value = collect($trimsBookingData)
                            ->sum('work_order_amount') ?? 0 +
                        (collect($shortTrimsBookingData)
                                ->sum('work_order_amount') ?? 0);

                    $trims_balance_qty = round($trims_budget_qty) - round($trims_booking_qty);
                    $trims_balance_value = $trims_budget_value - $trims_booking_value;

                    $trimsDetails['total_budget_qty'] = ($trimsDetails['total_budget_qty'] ?? 0) + $trims_budget_qty;
                    $trimsDetails['total_budget_value'] = ($trimsDetails['total_budget_value'] ?? 0) + $trims_budget_value;
                    $trimsDetails['total_booking_qty'] = ($trimsDetails['total_booking_qty'] ?? 0) + $trims_booking_qty;
                    $trimsDetails['total_booking_value'] = ($trimsDetails['total_booking_value'] ?? 0) + $trims_booking_value;
                    $trimsDetails['total_balance_qty'] = ($trimsDetails['total_balance_qty'] ?? 0) + $trims_balance_qty;
                    $trimsDetails['total_balance_value'] = ($trimsDetails['total_balance_value'] ?? 0) + $trims_balance_value;

                    return [
                        "item" => $trimsBudgetData['group_name'] ?? null,
                        "budget_qty" => round($trims_budget_qty) . " " . ($trimsBudgetData['cons_uom_value'] ?? null),
                        "budget_value" => number_format($trims_budget_value, 2),
                        "booking_qty" => round($trims_booking_qty) . " " . (collect($trimsBookingData)->first()->cons_uom_value ?? null),
                        "booking_value" => number_format($trims_booking_value, 2),
                        "balance_qty" => round($trims_balance_qty) . " " . ($trimsBudgetData['cons_uom_value'] ?? null),
                        "balance_value" => number_format($trims_balance_value, 2),
                    ];

                })->toArray();
        }

        return $trimsDetails;
    }

    /**
     * @param $budget
     * @return array
     */
    public function otherDetails($budget): array
    {
        $embellishmentBookingDetails = EmbellishmentWorkOrderDetails::query()
            ->where('budget_unique_id', $this->unique)->get();

        $embellishmentCost = $this->embellishmentCostDetails($budget, $embellishmentBookingDetails);
        $washCost = $this->washCostDetails($budget, $embellishmentBookingDetails);

        return collect($embellishmentCost)->merge($washCost)->toArray();
    }

    /**
     * @param $budget
     * @param $embellishmentBookingDetails
     * @return array
     */
    public function embellishmentCostDetails($budget, $embellishmentBookingDetails): array
    {
        $embellishmentDetails = [];
        if (isset($budget->embellishmentCosting['details']['details']) && count($budget->embellishmentCosting['details']['details'])) {

            $embellishmentDetails = $this->embellishmentWorkOrderFormat(
                $budget->embellishmentCosting['details']['details'],
                $embellishmentBookingDetails,
                'embellishment'
            );

        }

        return $embellishmentDetails;
    }

    /**
     * @param $budget
     * @param $embellishmentBookingDetails
     * @return array
     */
    public function washCostDetails($budget, $embellishmentBookingDetails): array
    {
        $washDetails = [];
        if (isset($budget->washCosting['details']['details']) && count($budget->washCosting['details']['details'])) {

            $washDetails = $this->embellishmentWorkOrderFormat(
                $budget->washCosting['details']['details'],
                $embellishmentBookingDetails,
                'wash'
            );

        }

        return $washDetails;
    }

    /**
     * @param $budgetData
     * @param $embellishmentBookingDetails
     * @return array
     */
    public function embellishmentWorkOrderFormat($budgetData, $embellishmentBookingDetails, $type): array
    {
        return collect($budgetData)
            ->map(function ($embellishmentBudgetData) use ($embellishmentBookingDetails, $type) {
                $embellishmentBookingData = isset($embellishmentBudgetData['name_id']) && isset($embellishmentBudgetData['type_id'])
                    ? collect($embellishmentBookingDetails)
                        ->where('embellishment_id', $embellishmentBudgetData['name_id'])
                        ->where('embellishment_type_id', $embellishmentBudgetData['type_id'])
                        ->all() : [];

                $embellishment_budget_qty = $embellishmentBudgetData['breakdown']['total_qty_sum'] ?? 0;
                $embellishment_budget_value = $embellishmentBudgetData['breakdown']['total_amount_sum'] ?? 0;
                $embellishment_booking_qty = collect($embellishmentBookingData)->sum('work_order_qty') ?? 0;
                $embellishment_booking_value = collect($embellishmentBookingData)->sum('total_amount') ?? 0;
                $embellishment_balance_qty = round($embellishment_budget_qty) - round($embellishment_booking_qty);
                $embellishment_balance_value = $embellishment_budget_value - $embellishment_booking_value;

                return [
                    "name" => $embellishmentBudgetData['name'] . " (" . $embellishmentBudgetData['type'] . ")",
                    "budget_qty" => $embellishment_budget_qty,
                    "budget_value" => $embellishment_budget_value,
                    "booking_qty" => $embellishment_booking_qty,
                    "booking_value" => $embellishment_booking_value,
                    "balance_qty" => $embellishment_balance_qty,
                    "balance_value" => $embellishment_balance_value,
                    "type" => $type
                ];
            })->toArray();
    }
}
