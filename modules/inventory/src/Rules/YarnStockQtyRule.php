<?php

namespace SkylarkSoft\GoRMG\Inventory\Rules;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

abstract class YarnStockQtyRule implements Rule
{
    public $yarn_count_id;
    public $yarn_composition_id;
    public $yarn_type_id;
    public $yarn_color;
    public $yarn_lot;
    public $uom_id;
    public $value;
    public $details_id;
    public $message;
    public $id;
    public $store_id;
    public $demand_no;
    public $receiveDetailOriginal;

    public $summary;

    public function setValues($value)
    {
        $this->yarn_count_id = request('yarn_count_id');
        $this->yarn_composition_id = request('yarn_composition_id');
        $this->yarn_type_id = request('yarn_type_id');
        $this->yarn_color = request('yarn_color');
        $this->yarn_lot = request('yarn_lot');
        $this->uom_id = request('uom_id');
        $this->details_id = request('id');
        $this->id = request('id');
        $this->store_id = request('store_id');
        $this->demand_no = request('demand_no');
        $this->value = $value;
        $this->receiveDetailOriginal = YarnReceiveDetail::query()->find(request('id'));
        $this->summary = (new YarnStockSummaryService())->summary(request()->all());
    }

    public function message(): string
    {
        return $this->message;
    }
}
