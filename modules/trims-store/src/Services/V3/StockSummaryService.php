<?php

namespace SkylarkSoft\GoRMG\TrimsStore\Services\V3;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\TrimsStore\Models\V3\TrimsStoreStockSummary;

class StockSummaryService
{
    private $criteria;

    /**
     * @param $collection
     */
    public function __construct($collection)
    {
        $this->criteria = [
            'factory_id' => $collection['factory_id'],
            'buyer_id' => $collection['buyer_id'],
            'style_id' => $collection['style_id'],
            'garments_item_id' => $collection['garments_item_id'],
            'item_id' => $collection['item_id'],
            'sensitivity_id' => $collection['sensitivity_id'],
            'supplier_id' => $collection['supplier_id'],
            'color_id' => $collection['color_id'],
            'size_id' => $collection['size_id'],
            'uom_id' => $collection['uom_id'],
            'floor_id' => $collection['floor_id'],
            'room_id' => $collection['room_id'],
            'rack_id' => $collection['rack_id'],
            'shelf_id' => $collection['shelf_id'],
            'bin_id' => $collection['bin_id'],
        ];
    }

    /**
     * @param $collection
     * @return StockSummaryService
     */
    public static function setCriteria($collection): StockSummaryService
    {
        return new static($collection);
    }

    /**
     * @return array
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    /**
     * @return Builder|Model|object|null
     */
    public function getStockSummary()
    {
        return TrimsStoreStockSummary::query()->where($this->criteria)->first();
    }

    /**
     * @return string
     */
    public function computeReceiveQty(): string
    {
        $stockSummary = $this->getStockSummary();
        $receiveQty = $stockSummary->receive_qty ?? 0;
        $receiveReturnQty = $stockSummary->receive_return_qty ?? 0;

        return format($receiveQty - $receiveReturnQty, 4);
    }

    /**
     * @return string
     */
    public function computeIssueQty(): string
    {
        $stockSummary = $this->getStockSummary();
        $issueQty = $stockSummary->issue_qty ?? 0;
        $issueReturnQty = $stockSummary->issue_return_qty ?? 0;

        return format($issueQty - $issueReturnQty, 4);
    }

    /**
     * @return string
     */
    public function computeBalanceQty(): string
    {
        $actualReceiveQty = $this->computeReceiveQty();
        $actualIssueQty = $this->computeIssueQty();

        return format($actualReceiveQty - $actualIssueQty, 4);
    }
}
