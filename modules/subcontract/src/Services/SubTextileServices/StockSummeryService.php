<?php

namespace SkylarkSoft\GoRMG\Subcontract\Services\SubTextileServices;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreStockSummaryReport;

class StockSummeryService
{
    private $criteria;

    public function __construct($collection)
    {
        $this->criteria = [
            'sub_textile_operation_id' => $collection->sub_textile_operation_id,
            'body_part_id' => $collection->body_part_id,
            'fabric_composition_id' => $collection->fabric_composition_id,
            'fabric_type_id' => $collection->fabric_type_id,
            'color_id' => $collection->color_id,
            'ld_no' => $collection->ld_no,
            'color_type_id' => $collection->color_type_id,
            'finish_dia' => $collection->finish_dia,
            'dia_type_id' => $collection->dia_type_id,
            'gsm' => $collection->gsm,
            'unit_of_measurement_id' => $collection->unit_of_measurement_id,
        ];
    }

    public static function setCriteria($collection): StockSummeryService
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
    public function getStockSummery()
    {
        return SubGreyStoreStockSummaryReport::query()->where($this->getCriteria())->first();
    }

    /**
     * @return HigherOrderBuilderProxy|int|mixed
     */
    public function computeReceiveQty()
    {
        $stockSummery = $this->getStockSummery();
        $receiveQty = $stockSummery->receive_qty ?? 0;
        $receiveReturnQty = $stockSummery->receive_return_qty ?? 0;
        $receiveTransferQty = $stockSummery->receive_transfer_qty ?? 0;
        $transferQty = $stockSummery->transfer_qty ?? 0;

        return ($receiveQty + $receiveTransferQty) - $receiveReturnQty - $transferQty;
    }

    /**
     * @return HigherOrderBuilderProxy|int|mixed
     */
    public function computeIssueQty()
    {
        $stockSummery = $this->getStockSummery();
        $issueQty = $stockSummery->issue_qty ?? 0;
        $issueReturnQty = $stockSummery->issue_return_qty ?? 0;

        return $issueQty - $issueReturnQty;
    }

    /**
     * @return HigherOrderBuilderProxy|int|mixed
     */
    public function getBalance()
    {
        return $this->computeReceiveQty() - $this->computeIssueQty();
    }
}
