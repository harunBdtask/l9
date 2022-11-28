<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Report;

use SkylarkSoft\GoRMG\Commercial\Models\SalesContractDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\BudgetService;
use SkylarkSoft\GoRMG\Merchandising\Services\Budget\Costings\CostingState;
use SkylarkSoft\GoRMG\Merchandising\Services\ReportSignatureService;
use SkylarkSoft\GoRMG\SystemSettings\Models\UnitOfMeasurement;

class FinalCostingReportService
{
    private $dzn;
    private $pcs;
    private $yds;
    private $grossConeRoll;

    private $order;
    private $data = array();

    public function reportData($request): array
    {
        $styleName = $request->get('style_name');
        $this->order = Order::query()->with(['uom', 'buyer', 'budgetData', 'dealingMerchant', 'purchaseOrders'])
            ->where('style_name', $styleName)
            ->first();

        $colorQtyArray = array();
        $colorQty = PoColorSizeBreakdown::query()->where('order_id', $this->order->id)->get();
        foreach ($colorQty as $po) {
            $qtyMatrixCollection = collect($po->quantity_matrix)->where('particular', 'Qty.')->groupBy('color');
            foreach ($qtyMatrixCollection as $key => $qtyMatrix) {
                foreach ($qtyMatrix as $qty) {
                    $colorQtyArray[$key][] = $qty;
                }
            }
        }

        $po = $this->order->purchaseOrders->pluck('id')->toArray();
        $data['order'] = $this->order;
        $data['colorQty'] = $colorQtyArray;
        $data['signature'] = ReportSignatureService::getSignatures("FINAL COSTING REPORT");
        $data['contractNumber'] = SalesContractDetail::query()->whereIn('po_id', $po)->first()->salesContract->contract_number ?? null;

        $types = [
            'fabric_costing',
            'trims_costing',
            'embellishment_cost',
            'wash_cost',
            'commercial_cost',
            'commission_cost',
        ];

        foreach ($types as $value) {
            $this->costingState($value);
        }

        $data['totalPOQty'] = $this->order->purchaseOrders->sum('po_quantity') ?? 0;
        $data['totalFOB'] = $this->order->purchaseOrders->sum('avg_rate_pc_set');
        $data['totalStyleValue'] = $data['totalPOQty'] * $data['totalFOB'];
        $data['totalPOQtyWithExcess'] = $data['totalPOQty']; // Some calculation will be added
        $data['totalStyleValueWithExcess'] = $data['totalStyleValue']; // Some calculation will be added

        $totalWashCost = collect($this->data)->where('type', 'wash')->sum('total_cost');
        $data['discountOrCommercialClause'] = 0;
        $data['totalCost'] = collect($this->data)->sum('total_cost');
        $data['totalFabricCost'] = collect($this->data)->where('type', 'fabric')->sum('total_cost');
        $data['totalAccCost'] = $data['totalCost'] - $data['totalFabricCost'];
        $data['totalCostDozen'] = $data['totalFabricCost'] + $data['totalAccCost'] + $data['discountOrCommercialClause'];
        //$data['totalFOB'] = $data['totalCostDozen'] - $data['totalPOQtyWithExcess'];
        $data['cmDozen'] = $data['totalFOB'] / $data['totalPOQtyWithExcess'] * 12;
        $data['totalFabricCostInPCS'] = $data['totalFabricCost'] / $data['totalPOQtyWithExcess'];
        $data['totalTrimsCost'] = $data['totalAccCost'] / $data['totalPOQtyWithExcess'];
        $data['washCostInPCS'] = $data['totalPOQtyWithExcess'] - $totalWashCost;
        $data['totalLCValue'] = $data['totalPOQty'] * $data['totalFOB'];
        $data['commercialCharge'] = $data['totalLCValue'] * 0.2;
        $data['netValue'] = $data['totalLCValue'] - $data['commercialCharge'];

        $data['costing'] = $this->data;
        return $data;
    }

    public function formatFabricCosting($costingData)
    {
        $costingData = $costingData['details']['details']['fabricForm'] ?? [];
        foreach ($costingData as $costing) {
            $details = $costing['greyConsForm'] ? collect($costing['greyConsForm']['details']) : collect([]);
            $margin = $costing['greyConsForm'] ? $costing['greyConsForm']['calculation']['process_loss_avg'] : 0;
            $consInPCS = $costing['grey_cons'] / 12 ?? 0;
            $uom = BudgetService::UOM[$costing['uom']];
            $costPerYds = $consInPCS * $costing['greyConsForm']['calculation']['rate_avg'];

            $this->setUOM($uom, $costPerYds);
            $this->data[] = [
                'type'                      => 'fabric_costing',
                'item'                      => $costing['fabric_composition_value'],
                'supplier'                  => $costing['supplier_value'],
                'nominated_status'          => '',
                'consumption_in_pcs'        => $consInPCS,
                'unit'                      => $uom ?? null,
                'consumption_in_dzn'        => $costing['grey_cons'],
                'margin'                    => $margin ?? 0,
                'consumption_with_margin'   => (($margin / 100) * $costing['grey_cons']) + $costing['grey_cons'],
                'total_req_qty'             => $costing['grey_cons_total_quantity'],
                'cost_per_yds'              => $this->yds,
                'cost_per_pcs'              => $this->pcs,
                'cost_per_dzn'              => $this->dzn,
                'cost_per_gross'            => $this->grossConeRoll,
                'total_cost'                => $costing['grey_cons_total_amount'],
            ];
        }
    }

    public function formatTrimsCosting($costingData)
    {
        $costingData = $costingData['details']['details'] ?? [];
        foreach ($costingData as $costing) {
            $consInPCS = $costing['cons_gmts'] / 12 ?? 0;
            $details = collect($costing['breakdown']['details']) ?? collect([]);
            $margin = $details->sum('ex_cons_percent') / count($details) ?? 0;
            $costPerUnit = $consInPCS * $costing['rate'];

            $this->setUOM($costing['cons_uom_value'], $costPerUnit);
            $this->data[] = [
                'type'                      => 'trims_costing',
                'item'                      => $costing['group_name'],
                'supplier'                  => $costing['nominated_supplier_value'],
                'nominated_status'          => '',
                'consumption_in_pcs'        => $consInPCS,
                'unit'                      => $costing['cons_uom_value'],
                'consumption_in_dzn'        => $costing['cons_gmts'],
                'margin'                    => $margin,
                'consumption_with_margin'   => (($margin / 100) * $costing['cons_gmts']) + $costing['cons_gmts'],
                'total_req_qty'             => $costing['total_quantity'],
                'cost_per_yds'              => $this->yds,
                'cost_per_pcs'              => $this->pcs,
                'cost_per_dzn'              => $this->dzn,
                'cost_per_gross'            => $this->grossConeRoll,
                'total_cost'                => $costing['total_amount'],
            ];
        }
    }

    public function formatEmblCosting($costingData)
    {
        $costingData = $costingData['details']['details'] ?? [];
        foreach ($costingData as $costing) {
            $margin = 0;
            $consInPCS = $costing['consumption'] / 12 ?? 0;
            $costPerUnit = $consInPCS * $costing['consumption_rate'];
            $item = $costing['breakdown']['details'][0]['item'] ?? '';
            $totalReqQty = collect($costing['breakdown']['details'])->sum('total_qty') ?? 0;

            $this->setUOM('', $costPerUnit);
            $this->data[] = [
                'type'                      => 'embellishment_cost',
                'item'                      => $item,
                'supplier'                  => $costing['supplier_value'],
                'nominated_status'          => '',
                'consumption_in_pcs'        => $consInPCS,
                'unit'                      => '',
                'consumption_in_dzn'        => $costing['consumption'],
                'margin'                    => $margin,
                'consumption_with_margin'   => (($margin / 100) * $costing['consumption']) + $costing['consumption'],
                'total_req_qty'             => $totalReqQty,
                'cost_per_yds'              => $this->yds,
                'cost_per_pcs'              => $this->pcs,
                'cost_per_dzn'              => $this->dzn,
                'cost_per_gross'            => $this->grossConeRoll,
                'total_cost'                => $totalReqQty * $costing['consumption_rate'],
            ];
        }
    }

    public function formatWashCosting($costingData)
    {
        $costingData = $costingData['details']['details'] ?? [];
        foreach ($costingData as $costing) {
            $consInPCS = $costing['consumption'] / 12 ?? 0;
            $margin = $costing['consumption_process_loss'] ?? 0;
            $costPerUnit = $consInPCS * $costing['consumption_rate'];
            $totalReqQty = collect($costing['breakdown']['details'])->sum('total_qty') ?? 0;

            $this->setUOM('', $costPerUnit);
            $this->data[] = [
                'type'                  => 'wash_cost',
                'item'                  => $costing['type'],
                'supplier'              => $costing['supplier_value'],
                'nominated_status'      => '',
                'consumption_in_pcs'    => $consInPCS,
                'unit'                  => '',
                'consumption_in_dzn'    => $costing['consumption'],
                'margin'                => $margin,
                'consumption_with_margin' => (($margin / 100) * $costing['consumption']) + $costing['consumption'],
                'total_req_qty'         => $totalReqQty,
                'cost_per_yds'          => $this->yds,
                'cost_per_pcs'          => $this->pcs,
                'cost_per_dzn'          => $this->dzn,
                'cost_per_gross'        => $this->grossConeRoll,
                'total_cost'            => $totalReqQty * $costing['consumption_rate'],
            ];
        }
    }

    public function formatCommercialCosting($costingData)
    {
        $costingData = $costingData['details']['details'] ?? [];
        foreach ($costingData as $costing) {
            $this->data[] = [
                'type'                      => 'commercial_cost',
                'item'                      => $costing['name'],
                'supplier'                  => '',
                'nominated_status'          => '',
                'consumption_in_pcs'        => 0,
                'unit'                      => '',
                'consumption_in_dzn'        => 0,
                'margin'                    => 0,
                'consumption_with_margin'   => 0,
                'total_req_qty'             => 0,
                'cost_per_yds'              => 0,
                'cost_per_pcs'              => 0,
                'cost_per_dzn'              => 0,
                'cost_per_gross'            => 0,
                'total_cost'                => $costing['amount'],
            ];
        }
    }

    public function formatCommissionCosting($costingData)
    {
        $costingData = $costingData['details']['details'] ?? [];
        foreach ($costingData as $costing) {
            $this->data[] = [
                'type'                      => 'commission_cost',
                'item'                      => $costing['particular_name'],
                'supplier'                  => '',
                'nominated_status'          => '',
                'consumption_in_pcs'        => 0,
                'unit'                      => '',
                'consumption_in_dzn'        => 0,
                'margin'                    => 0,
                'consumption_with_margin'   => 0,
                'total_req_qty'             => 0,
                'cost_per_yds'              => 0,
                'cost_per_pcs'              => 0,
                'cost_per_dzn'              => 0,
                'cost_per_gross'            => 0,
                'total_cost'                => $costing['amount'],
            ];
        }
    }

    public function setUOM($uomValue, $cost)
    {
        $this->yds = null;
        $this->pcs = null;
        $this->dzn = null;
        $this->grossConeRoll = null;

        if ($uomValue == 'Yds'  || $uomValue == 'Yards') {
            $this->yds = $cost;
        } elseif ($uomValue == 'Pcs') {
            $this->pcs = $cost;
        } elseif ($uomValue == 'Grs' || $uomValue == 'Cone' || $uomValue == 'Roll') {
            $this->grossConeRoll = $cost;
        } else {
            $this->dzn = $cost;
        }
    }

    public function costingState($type)
    {
        $budget = BudgetCostingDetails::query()->where([
            'budget_id' => $this->order->budgetData->id,
            'type' => $type,
        ])->first();

        $state = CostingState::setState($type);
        $data = $state->format($budget, $budget, $type);

        switch ($type) {
            case 'fabric_costing':
                $this->formatFabricCosting($data);
                break;
            case 'trims_costing':
                $this->formatTrimsCosting($data);
                break;
            case 'embellishment_cost':
                $this->formatEmblCosting($data);
                break;
            case 'wash_cost':
                $this->formatWashCosting($data);
                break;
            case 'commercial_cost':
                $this->formatCommercialCosting($data);
                break;
            case 'commission_cost':
                $this->formatCommissionCosting($data);
                break;
        }
    }
}
