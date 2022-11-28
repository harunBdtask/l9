<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;

class FabricIssueQtyRule extends FabricStockQtyRule
{
    public function passes($attribute, $value): bool
    {
        $passed = true;
        $this->setFabric($attribute, $value);

        $balance = $this->summary->balance;
        $totalIssueQty = $this->summary->issue_qty;
        $totalIssueReturnQty = $this->summary->issue_return_qty;

        /* issue qty edit */
        if (!$this->fabric['id']) {
            $this->message = "Issue qty $value cannot be greater than stock balance $balance!";

            return $value <= $balance;
        }

        /* Issue QTY update */
        $oldIssueQty = $this->oldIssueQty();

        if ($value > $oldIssueQty) {
            $this->message = "Issue qty $value cannot be greater than stock balance " . ($balance + $oldIssueQty) . "!";
            $passed = $value - $oldIssueQty <= $balance;
        }

        /* Issue Qty Decreasing */
        if ($value < $oldIssueQty) {
            $passed = $totalIssueQty - ($oldIssueQty - $value) >= $totalIssueReturnQty;
        }

        return $passed;
    }

    private function oldIssueQty()
    {
        return FabricIssueDetail::query()->where('id', $this->fabric['id'])->sum('issue_qty');
    }

    public function setFabric($attribute, $value)
    {
        $this->fabric['id'] = request('id');
        $this->fabric['batch_no'] = request('batch_no');
        $this->fabric['style_id'] = request('style_id');
        $this->fabric['gmts_item_id'] = request('gmts_item_id');
        $this->fabric['body_part_id'] = request('body_part_id');
        $this->fabric['color_type_id'] = request('color_type_id');
        $this->fabric['color_id'] = request('color_id');
        $this->fabric['construction'] = request('construction');
        $this->fabric['fabric_description'] = request('fabric_description');
        $this->fabric['uom_id'] = request('uom_id');
        $this->fabric['fabric_composition_id'] = request('fabric_composition_id');
        $this->fabric['dia_type'] = request('dia_type');
        $this->fabric['dia'] = request('dia');
        $this->fabric['ac_dia'] = request('ac_dia');
        $this->fabric['gsm'] = request('gsm');
        $this->fabric['ac_gsm'] = request('ac_gsm');
        $this->fabric['store_id'] = request('store_id');
        $this->fabric['qty'] = $value;

        $this->summary = (new FabricStockSummaryService())
            ->summary($this->fabric);
    }
}
