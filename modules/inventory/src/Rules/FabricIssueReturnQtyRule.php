<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;

class FabricIssueReturnQtyRule extends FabricStockQtyRule
{

    public function passes($attribute, $value): bool
    {
        $this->setFabric($attribute, $value);

        /* Max issue return qty is equals to issue qty*/
        if ((string)($this->summary->issue_qty - $this->summary->issue_return_qty) >= $value) {
            return true;
        }

        $this->message = 'Return Qty Can not be greater than Issue qty';

        return false;
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
