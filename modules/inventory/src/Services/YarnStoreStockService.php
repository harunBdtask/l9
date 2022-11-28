<?php

namespace SkylarkSoft\GoRMG\Inventory\Services;

use Exception;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;

class YarnStoreStockService
{
    protected $yarnUpdate = [];
    protected $actualQty = null;
    protected $yarnStockSummary;
    protected $isIncrease = true;
    public function __construct($yarn, $date)
    {
        $yarnQuery['uom_id'] = $yarn->uom_id;
        $yarnQuery['yarn_lot'] = $yarn->yarn_lot;
        $yarnQuery['store_id'] = $yarn->store_id;
        $yarnQuery['yarn_color'] = $yarn->yarn_color;
        $yarnQuery['yarn_type_id'] = $yarn->yarn_type_id;
        $yarnQuery['yarn_count_id'] = $yarn->yarn_count_id;
        $this->yarnStockSummary = YarnStockSummary::query()->where($yarnQuery);
    }

    public function setStockToReceive($yarn)
    {
        $actualQty = $yarn->receive_qty - $yarn->getOriginal('receive_qty');
        $this->yarnUpdate = [
            'receive_qty' => $actualQty,
            'receive_amount' => $actualQty * $yarn->rate,
        ];
        return $this;
    }

    public function setStockToIssue($yarn)
    {
        $this->stockSummary();
        $this->isIncrease = false;
        $this->actualQty = $yarn->issue_qty - $yarn->getOriginal('issue_qty');
        $this->yarnUpdate = ['issue_qty' => $this->actualQty];
        return $this;
    }

    public function setStockToIssueReturn($yarn)
    {
        $this->stockSummary();
        $this->actualQty = $yarn->return_qty - $yarn->getOriginal('return_qty');
        $this->yarnUpdate = ['return_qty' => $this->actualQty];
        return $this;
    }

    public function setStockToReceiveReturn($yarn)
    {
        $this->stockSummary();
        $this->actualQty = $yarn->return_qty - $yarn->getOriginal('return_qty');
        $this->yarnUpdate = ['return_qty' => $this->actualQty];
        return $this;
    }

    public function setStockToTransfer($yarnFrom, $yarnTo)
    {
        $this->stockSummary();
    }

    protected function stockSummary()
    {
        try {
            $yarnStockSummary = YarnStockSummary::query()
                ->firstOrFail($this->yarnQuery);
            if ($this->isIncrease) {
                $balanceQty = $yarnStockSummary->balance + $this->actualQty;
            } else {
                $balanceQty = $yarnStockSummary->balance - $this->actualQty;
            }
            $this->yarnUpdate['balance'] = $balanceQty;
        } catch (Exception $exception) {
            $this->yarnUpdate['balance'] = $this->actualQty;
            $yarnStockSummary = YarnStockSummary::query()
                ->create(array_merge($this->yarnQuery, $this->yarnUpdate));
        }

    }
    public function destory()
    {
        try {
            YarnStockSummary::query()->where($this->yarnQuery)->delete();
            YarnDateWiseStockSummary::query()->where($this->yarnQuery)->delete();
        } catch (Exception $exception) {
            return false;
        }
    }
}
