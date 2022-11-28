<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\FabricStore\MaxReceiveQtyInterface;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsStockSummeryService;

abstract class FabricStockQtyRule implements Rule
{
    public $message;

    public $summary; /* Stock summary for item, with specific Style and UOM*/

    public $fabric = [];

    public function setFabric($attribute, $value)
    {
        $idx = 'details.' . explode('.', $attribute)[1];
        $this->fabric['id'] = request($idx . '.id');
        $this->fabric['batch_no'] = request($idx . '.batch_no');
        $this->fabric['style_id'] = request($idx . '.style_id');
        $this->fabric['gmts_item_id'] = request($idx . '.gmts_item_id');
        $this->fabric['body_part_id'] = request($idx . '.body_part_id');
        $this->fabric['color_type_id'] = request($idx . '.color_type_id');
        $this->fabric['color_id'] = request($idx . '.color_id');
        $this->fabric['construction'] = request($idx . '.construction');
        $this->fabric['fabric_description'] = request($idx . '.fabric_description');
        $this->fabric['uom_id'] = request($idx . '.uom_id');
        $this->fabric['fabric_composition_id'] = request($idx . '.fabric_composition_id');
        $this->fabric['dia_type'] = request($idx . '.dia_type');
        $this->fabric['dia'] = request($idx . '.dia');
        $this->fabric['ac_dia'] = request($idx . '.ac_dia');
        $this->fabric['gsm'] = request($idx . '.gsm');
        $this->fabric['ac_gsm'] = request($idx . '.ac_gsm');
        $this->fabric['store_id'] = request($idx . '.store_id');
        $this->fabric['qty'] = $value;

        $this->summary = (new FabricStockSummaryService())
            ->summary($this->fabric);
    }

    public function summary()
    {
        return $this->summary;
    }

    public function message()
    {
        return $this->message;
    }

    public function maxReceiveQty(MaxReceiveQtyInterface $maxReceiveQty)
    {
        return $maxReceiveQty->maxReceiveQty($this->fabric);
    }

    public function getReceiveReturnDetail()
    {
        return FabricReceiveReturnDetail::query()->find($this->fabric['id']);
    }
}
