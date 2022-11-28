<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Rules\V3;

use Illuminate\Contracts\Validation\Rule;
use SkylarkSoft\GoRMG\TrimsStore\Services\V3\StockSummaryService;

abstract class TrimsStockQtyRule implements Rule
{
    public $id;
    public $criteria;
    public $stockSummary;
    public $summary;
    public $balance;
    public $message;

    public function setValues()
    {
        $this->id = request('id');
        $this->criteria = request()->all();
        $this->stockSummary = (new StockSummaryService($this->criteria));
        $this->summary = $this->stockSummary->getStockSummary();
        $this->balance = $this->stockSummary->computeBalanceQty();
    }

    public function message(): string
    {
        return $this->message;
    }
}
