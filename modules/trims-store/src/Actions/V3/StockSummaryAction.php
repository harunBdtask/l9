<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Actions\V3;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreDailyStockSummary;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssue\TrimsStoreIssueDetail;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreIssueReturn\TrimsStoreIssueReturnDetail;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceive\TrimsStoreReceiveDetail;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturnDetail;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreStockSummary;

class StockSummaryAction
{
    /**
     * @param $detail
     * @return void
     */
    public function attachToStockSummary($detail)
    {
        $stockSummary = $this->firstOrNewStockSummary($detail);

        if ($detail instanceof TrimsStoreReceiveDetail) {
            $stockSummary->update([
                'receive_qty' => $this->computeReceiveQty($detail),
                'receive_reject_qty' => $this->computeReceiveRejectQty($detail),
                'receive_return_qty' => $this->computeReceiveReturnQty($detail),
            ]);
        }

        if ($detail instanceof TrimsStoreReceiveReturnDetail) {
            $stockSummary->update([
                'receive_qty' => $this->computeReceiveQty($detail),
                'receive_reject_qty' => $this->computeReceiveRejectQty($detail),
                'receive_return_qty' => $this->computeReceiveReturnQty($detail),
            ]);
        }

        if ($detail instanceof TrimsStoreIssueDetail) {
            $stockSummary->update([
                'issue_qty' => $this->computeIssueQty($detail),
                'issue_return_qty' => $this->computeIssueReturnQty($detail),
            ]);
        }
    }

    /**
     * @param $detail
     * @return void
     */
    public function attachToDailyStockSummary($detail)
    {
        $dailyStockSummary = $this->firstOrNewDailyStockSummary($detail);

        if ($detail instanceof TrimsStoreReceiveDetail) {
            $dailyStockSummary->update([
                'receive_qty' => $this->computeReceiveQty($detail, true),
                'receive_reject_qty' => $this->computeReceiveRejectQty($detail, true),
                'receive_return_qty' => $this->computeReceiveReturnQty($detail, true),
            ]);
        }

        if ($detail instanceof TrimsStoreReceiveReturnDetail) {
            $dailyStockSummary->update([
                'receive_qty' => $this->computeReceiveQty($detail),
                'receive_reject_qty' => $this->computeReceiveRejectQty($detail),
                'receive_return_qty' => $this->computeReceiveReturnQty($detail, true),
            ]);
        }

        if ($detail instanceof TrimsStoreIssueDetail) {
            $dailyStockSummary->update([
                'issue_qty' => $this->computeIssueQty($detail),
                'issue_return_qty' => $this->computeIssueReturnQty($detail, true),
            ]);
        }
    }

    /**
     * @param $detail
     * @param $date
     * @return array
     */
    private function setCriteria($detail, $date = false): array
    {
        $criteria = [
            'factory_id' => $detail->factory_id,
            'buyer_id' => $detail->buyer_id,
            'style_id' => $detail->style_id,
            'garments_item_id' => $detail->garments_item_id,
            'item_id' => $detail->item_id,
            'item_description' => $detail->item_description,
            'sensitivity_id' => $detail->sensitivity_id,
            'supplier_id' => $detail->supplier_id,
            'color_id' => $detail->color_id,
            'size_id' => $detail->size_id,
            'uom_id' => $detail->uom_id,
            'floor_id' => $detail->floor_id,
            'room_id' => $detail->room_id,
            'rack_id' => $detail->rack_id,
            'shelf_id' => $detail->shelf_id,
            'bin_id' => $detail->bin_id,
        ];

        if ($date) {
            $criteria['transaction_date'] = $detail['transaction_date'];
        }

        return $criteria;
    }

    /**
     * @param $detail
     * @return Builder|Model
     */
    private function firstOrNewStockSummary($detail)
    {
        return TrimsStoreStockSummary::query()->updateOrCreate(
            $this->setCriteria($detail),
            $detail->toArray()
        );
    }

    /**
     * @param $detail
     * @return Builder|Model
     */
    private function firstOrNewDailyStockSummary($detail)
    {
        return TrimsStoreDailyStockSummary::query()->updateOrCreate(
            $this->setCriteria($detail, true),
            $detail->toArray()
        );
    }

    /**
     * @param $detail
     * @param $date
     * @return int|mixed
     */
    private function computeReceiveQty($detail, $date = false)
    {
        return TrimsStoreReceiveDetail::query()
            ->where($this->setCriteria($detail, $date))
            ->sum('receive_qty');
    }

    /**
     * @param $detail
     * @param bool $date
     * @return int|mixed
     */
    private function computeReceiveRejectQty($detail, $date = false)
    {
        return TrimsStoreReceiveDetail::query()
            ->where($this->setCriteria($detail, $date))
            ->sum('reject_qty');
    }

    /**
     * @param $detail
     * @param $date
     * @return int|mixed
     */
    private function computeReceiveReturnQty($detail, $date = false)
    {
        return TrimsStoreReceiveReturnDetail::query()
            ->where($this->setCriteria($detail, $date))
            ->sum('receive_return_qty');
    }

    /**
     * @param $detail
     * @param $date
     * @return int|mixed
     */
    private function computeIssueQty($detail, $date = false)
    {
        return TrimsStoreIssueDetail::query()
            ->where($this->setCriteria($detail, $date))
            ->sum('issue_qty');
    }

    /**
     * @param $detail
     * @param $date
     * @return int|mixed
     */
    private function computeIssueReturnQty($detail, $date = false)
    {
        return TrimsStoreIssueReturnDetail::query()
            ->where($this->setCriteria($detail, $date))
            ->sum('issue_return_qty');
    }
}
