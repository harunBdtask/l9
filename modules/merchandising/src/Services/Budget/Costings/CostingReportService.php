<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings;

use phpDocumentor\Reflection\Types\This;
use SkylarkSoft\GoRMG\Commercial\Models\ExportLCDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;

class CostingReportService
{
    public static function reportData($id)
    {
        $data = [];
        $budget = Budget::with(['buyer:id,name', 'order.purchaseOrders', 'order.purchaseOrders.poDetails', 'order.buyer:id,name',
            'order.teamLeader', 'order.dealingMerchant', 'order.factoryMerchant'
            , 'order.buyingAgent:id,buying_agent_name', 'order.assignFactory:id,name',])->findOrFail($id);
        $data['id'] = $budget ? $budget['id'] : '';
        $data['order'] = $budget ? self::orderData($budget) : null;
        $data['fabricDetails'] = $budget ? self::fabricDetails($budget) : null;
        $data['trimsDetails'] = $budget ? self::trimsDetails($budget) : null;
        $data['lc'] = $budget ? self::lcData($budget) : null;
        $data['otherCostings'] = $budget ? self::otherCostData($budget, collect($data['order']['poDetails'])->sum('po_qty')) : null;
        $data['supplierWiseSales'] = $budget ? self::supplierWiseSales($data['fabricDetails'], $data['trimsDetails']) : null;
        return $data;


    }

    public static function orderData($data)
    {
        $order = [];
        $order['buyer_name'] = $data['order']->buyer->name ?? '';
        $order['assign_factory_name'] = $data['order']->assignFactory->name ?? '';
        $order['team_name'] = $data['order']->teamLeader->screen_name ?? '';
        $order['buying_agent'] = $data['order']->buyingAgent->buying_agent_name ?? '';
        $order['merchandiser_name'] = $data['order']->dealingMerchant->screen_name ?? '';
        $order['factory_merchandiser_name'] = $data['order']->factoryMerchant->screen_name ?? '';
        $order['budgetImage'] = $data['image'] ?? '';
        $uom = self::orderUom($data['order']->order_uom_id);
        $orderData = $data['order'];
        $order['uom'] = self::orderUom($data['order']->order_uom_id);

        $order['poDetails'] = collect($orderData['purchaseOrders'])->map(function ($item) use ($orderData, $uom) {
            return [
                'po_no' => $item['po_no'],
                'style_name' => $orderData['style_name'],
                'style_description' => $orderData['style_description'],
                'po_qty' => $item['po_quantity'] ?? 0,
                'shipment_date' => $item['ex_factory_date'],
                'unit_price' => $item['avg_rate_pc_set'] ?? 0,
                'remarks' => $item['remarks'],
                'uom' => $uom
            ];
        });
        return $order;

    }

    public static function fabricDetails($data)
    {
        return collect($data->fabricDetails())->map(function ($item) {
            return [
                'fabric_description' => $item['fabric_composition_value'],
                'cons' => $item['grey_cons'],
                'total_qty' => $item['grey_cons_total_quantity'],
                'extra' => $item['greyConsForm']['calculation']['process_loss_avg'] ?? 0,
                'fabricUom' => $item['uom'] ? Budget::UOM[$item['uom']] : '',
                'rate' => $item['grey_cons_rate'] ?? 0,
                'total_amount' => $item['grey_cons_total_amount'] ?? 0,
                'supplier' => $item['supplier_value'] ?? '',
                'remarks' => $item['remarks'] ?? '',
            ];
        });
    }

    public static function trimsDetails($data)
    {
        return collect($data->trimDetails())->map(function ($item) {
            return [
                'trims_description' => $item['group_name'],
                'cons' => $item['cons_gmts'],
                'total_qty' => $item['total_quantity'],
                'extra' => collect($item['breakdown']['details'])->avg('ext_cons_percent'),
                'fabricUom' => $item['cons_uom_value'] ?: '',
                'rate' => $item['rate'] ?? 0,
                'total_amount' => $item['total_amount'] ?? 0,
                'supplier' => $item['nominated_supplier_value'] ?? '',
                'remarks' => $item['remarks'] ?? '',
                'amount' => $item['amount'] ?? 0,
            ];
        });
    }

    public static function lcData($data)
    {
        $orderId = $data['order']->id;
        $attached_value = ExportLCDetail::query()->with('salesContract')->where('order_id', $orderId)->get();
        $lc = [];
        $lc['lc_sc_total_value'] = $attached_value->sum('attach_value');
        $lc['unique_id'] = count($attached_value) > 0 ? $attached_value->first()['salesContract']['unique_id'] : '';
        $lc['lc_number'] = count($attached_value) > 0 ? $attached_value->first()['salesContract']['lc_number'] : '';
//        $lc['lc_date'] = $attached_value->first()['salesContract']['lc_date'];
//        $lc['created_at'] = $attached_value->first()['salesContract']['created_at'];
//        $lc['lc_value'] = $attached_value->first()['salesContract']['lc_value'];
        return $lc;
    }

    public static function otherCostData($data, $orderQty)
    {
        $costs = [];
        $costs['orderQtyPerPcs'] = $orderQty;
        $costs['orderQtyPerDzn'] = (double)$orderQty / 12;
        $costingMultiplexer = $data->costing_multiplier;
        $setRatio = $data['order']['item_details'] ? $data['order']['item_details']['calculation']['total_item_ratio'] : 1;

//        $lab_test = array_get($data['costing']['lab_test']['budgeted_cost')];
        $lab_test = array_get($data['costing'], 'lab_test.budgeted_cost', 0);
        $costs['lab_test'] = self::getTotalAmount($orderQty, $costingMultiplexer, $setRatio, $lab_test);

        $inspection = array_get($data['costing'], 'inspection.budgeted_cost', 0);
        $costs['inspection'] = self::getTotalAmount($orderQty, $costingMultiplexer, $setRatio, $inspection);
        $costs['lab_inspection'] = $costs['lab_test'] + $costs['inspection'];

        $commercial_cost = array_get($data['costing'], 'comml_cost.budgeted_cost', 0);
        $costs['commercial_cost'] = $commercial_cost;
        $costs['commercial_cost_total'] = self::getTotalAmount($orderQty, $costingMultiplexer, $setRatio, $commercial_cost);

        $cm_cost = array_get($data['costing'], 'cm_cost.budgeted_cost', 0);
        $costs['cm_cost'] = $cm_cost;
        $costs['cm_cost_total'] = self::getTotalAmount($orderQty, $costingMultiplexer, $setRatio, $cm_cost);

//        $printingCost = collect($data->embellishmentDetails()->where('name_id', 1)->all())->sum('consumption_amount');
//        $costs['printing_cost'] = $printingCost;
//        $costs['printing_cost_total'] = 0;


//        return $data->embellishmentDetails();
//        $embCost = collect($data->embellishmentDetails()->where('name_id', 18)->all())->sum('consumption_amount');
        $embellishmentCost = $data->embellishmentDetails() ? self::formatEmblCost($data->embellishmentDetails(), $orderQty, $costingMultiplexer, $setRatio,) : [];
        $costs['emb_cost'] = $embellishmentCost;
        $costs['emb_cost_total'] = collect($costs['emb_cost'])->sum('emb_cost_total');
//        $costs['emb_cost_total'] = self::getTotalAmount($orderQty, $costingMultiplexer, $setRatio, $embCost);

        $costs['sumOthers'] = $costs['lab_inspection'] + $costs['commercial_cost_total'] + $costs['cm_cost_total']
            + $costs['emb_cost_total'];

        return $costs;
    }

    private static function formatEmblCost($data, $orderQty, $costingMultiplexer, $setRatio)
    {
        return collect($data)->map(function ($item) use ($orderQty, $costingMultiplexer, $setRatio) {
            $cost = $item['consumption_amount'] ?? 0;
            $perDzn = $item['consumption'] ?? 0;
            return [
                'per_dzn' => (float)$perDzn,
                'per_pcs' => $perDzn * $costingMultiplexer,
                'name' => $item['name'],
                'emb_cost' => $cost,
                'emb_cost_total' => self::getTotalAmount($orderQty, $costingMultiplexer, $setRatio, $cost)
            ];
        });
    }

    private static function getTotalAmount($orderQty, $costingPer, $setRatio, $rate)
    {
        return ($costingPer * $setRatio) != 0 ? ($orderQty / ($costingPer * $setRatio)) * $rate : 0;
    }

    public static function orderUom($uomId)
    {
        return $uomId == 1 ? 'Pcs' : ($uomId == 2 ? 'Set' : '');
    }

    private static function supplierWiseSales($fabric, $trims)
    {
        $fabricSupplier = collect($fabric)->groupBy('supplier')->map(function ($fabric, $key) {
            return [
                'supplier' => $key,
                'total_amount' => collect($fabric)->sum('total_amount'),
                'type' => 'Fabric',
                'remarks' => collect($fabric)->first()['remarks'] ?? '',
            ];
        })->values();
        $trimsSupplier = collect($trims)->groupBy('supplier')->map(function ($fabric, $key) {
            return [
                'supplier' => $key,
                'total_amount' => collect($fabric)->sum('total_amount'),
                'type' => 'Accessories',
                'remarks' => collect($fabric)->first()['remarks'] ?? '',
            ];
        })->values();
        $suppliers = array_merge(array($fabricSupplier), array($trimsSupplier));

        return collect($suppliers)->flatten(1);

    }

}
