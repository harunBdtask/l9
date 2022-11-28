<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;

class FabricReceiveReturnQtyRule extends FabricStockQtyRule
{

    public function passes($attribute, $value): bool
    {
        $this->setFabric($attribute, $value);
        if (!$this->fabric['id']) {
            $this->message = 'Insufficient balance!';

            return $value <= $this->summary->balance;
        }

        $detail = $this->getReceiveReturnDetail();

        if (!$detail) {
            $this->message = 'Detail is not found for this id - ( ' . $this->fabric['id'] . ' )';

            return false;
        }

        $this->message = 'Can not be edited!';

        return $detail->return_qty == $value;
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
