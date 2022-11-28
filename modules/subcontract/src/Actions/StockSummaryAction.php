<?php

namespace SkylarkSoft\GoRMG\Subcontract\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreDailyStockSummaryReport;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreIssueDetail;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreReceiveDetails;
use SkylarkSoft\GoRMG\Subcontract\Models\SubTextileModels\SubGreyStoreStockSummaryReport;

class StockSummaryAction
{
    /**
     * @param $fabricDetails
     * @param $date
     * @return array
     */
    public function setCriteria($fabricDetails, $date): array
    {
        $criteria = [
            'sub_textile_operation_id' => $fabricDetails->sub_textile_operation_id,
            'body_part_id' => $fabricDetails->body_part_id,
            'fabric_composition_id' => $fabricDetails->fabric_composition_id,
            'fabric_type_id' => $fabricDetails->fabric_type_id,
            'color_id' => $fabricDetails->color_id,
            'ld_no' => $fabricDetails->ld_no,
            'color_type_id' => $fabricDetails->color_type_id,
            'finish_dia' => $fabricDetails->finish_dia,
            'dia_type_id' => $fabricDetails->dia_type_id,
            'gsm' => $fabricDetails->gsm,
            'unit_of_measurement_id' => $fabricDetails->unit_of_measurement_id,
        ];

        if ($date) {
            $criteria['challan_date'] = $fabricDetails->challan_date;
        }

        return $criteria;
    }

    /**
     * @param $fabricDetails
     * @return void
     */
    public function attachToStockSummaryReport($fabricDetails)
    {
        $subGreyStockSummary = $this->firstOrNewStockSummary($fabricDetails);
        if ($fabricDetails instanceof SubGreyStoreReceiveDetails) {
            $subGreyStockSummary->update([
                'receive_qty' => $this->computeReceiveQty($fabricDetails),
                'total_receive_roll' => $this->computeReceiveRoll($fabricDetails),
                'receive_return_qty' => $this->computeReceiveReturnQty($fabricDetails),
                'return_receive_roll' => $this->computeReceiveReturnRoll($fabricDetails),
            ]);
        }
        if ($fabricDetails instanceof SubGreyStoreIssueDetail) {
            $subGreyStockSummary->update([
                'issue_qty' => $this->computeIssueQty($fabricDetails),
                'total_issue_roll' => $this->computeIssueRoll($fabricDetails),
                'issue_return_qty' => $this->computeIssueReturnQty($fabricDetails),
                'return_issue_roll' => $this->computeIssueReturnRoll($fabricDetails),
            ]);
        }
    }

    /**
     * @param $fabricDetails
     * @return void
     */
    public function attachToDailyStockSummaryReport($fabricDetails)
    {
        $subGreyStockSummary = $this->firstOrNewDailyStockSummary($fabricDetails);
        if ($fabricDetails instanceof SubGreyStoreReceiveDetails) {
            $subGreyStockSummary->update([
                'receive_qty' => $this->computeReceiveQty($fabricDetails, true),
                'total_receive_roll' => $this->computeReceiveRoll($fabricDetails, true),
                'receive_return_qty' => $this->computeReceiveReturnQty($fabricDetails, true),
                'return_receive_roll' => $this->computeReceiveReturnRoll($fabricDetails, true),
            ]);
        }
        if ($fabricDetails instanceof SubGreyStoreIssueDetail) {
            $subGreyStockSummary->update([
                'issue_qty' => $this->computeIssueQty($fabricDetails, true),
                'total_issue_roll' => $this->computeIssueRoll($fabricDetails, true),
                'issue_return_qty' => $this->computeIssueReturnQty($fabricDetails, true),
                'return_issue_roll' => $this->computeIssueReturnRoll($fabricDetails, true),
            ]);
        }
    }

    /**
     * @param $fabricDetails
     * @param $date
     * @return int|mixed
     */
    private function computeReceiveQty($fabricDetails, $date = false)
    {
        return SubGreyStoreReceiveDetails::query()
            ->where($this->setCriteria($fabricDetails, $date))
            ->sum('receive_qty');
    }

    /**
     * @param $fabricDetails
     * @param $date
     * @return int|mixed
     */
    private function computeReceiveRoll($fabricDetails, $date = false)
    {
        return SubGreyStoreReceiveDetails::query()
            ->where($this->setCriteria($fabricDetails, $date))
            ->sum('total_roll');
    }

    /**
     * @param $fabricDetails
     * @param $date
     * @return int|mixed
     */
    private function computeReceiveReturnQty($fabricDetails, $date = false)
    {
        return SubGreyStoreReceiveDetails::query()
            ->where($this->setCriteria($fabricDetails, $date))
            ->sum('receive_return_qty');
    }

    /**
     * @param $fabricDetails
     * @param $date
     * @return int|mixed
     */
    private function computeReceiveReturnRoll($fabricDetails, $date = false)
    {
        return SubGreyStoreReceiveDetails::query()
            ->where($this->setCriteria($fabricDetails, $date))
            ->sum('return_roll');
    }

    /**
     * @param $fabricDetails
     * @param $date
     * @return int|mixed
     */
    private function computeIssueQty($fabricDetails, $date = false)
    {
        return SubGreyStoreIssueDetail::query()
            ->where($this->setCriteria($fabricDetails, $date))
            ->sum('issue_qty');
    }

    /**
     * @param $fabricDetails
     * @param $date
     * @return int|mixed
     */
    private function computeIssueRoll($fabricDetails, $date = false)
    {
        return SubGreyStoreIssueDetail::query()
            ->where($this->setCriteria($fabricDetails, $date))
            ->sum('total_roll');
    }

    /**
     * @param $fabricDetails
     * @param $date
     * @return int|mixed
     */
    private function computeIssueReturnQty($fabricDetails, $date = false)
    {
        return SubGreyStoreIssueDetail::query()
            ->where($this->setCriteria($fabricDetails, $date))
            ->sum('issue_return_qty');
    }

    /**
     * @param $fabricDetails
     * @param $date
     * @return int|mixed
     */
    private function computeIssueReturnRoll($fabricDetails, $date = false)
    {
        return SubGreyStoreIssueDetail::query()
            ->where($this->setCriteria($fabricDetails, $date))
            ->sum('return_roll');
    }

    /**
     * @param $fabricDetails
     * @return Builder|Model
     */
    private function firstOrNewStockSummary($fabricDetails)
    {
        $criteria = [
            'sub_textile_operation_id' => $fabricDetails->sub_textile_operation_id,
            'body_part_id' => $fabricDetails->body_part_id,
            'fabric_composition_id' => $fabricDetails->fabric_composition_id,
            'fabric_type_id' => $fabricDetails->fabric_type_id,
            'color_id' => $fabricDetails->color_id,
            'ld_no' => $fabricDetails->ld_no,
            'color_type_id' => $fabricDetails->color_type_id,
            'finish_dia' => $fabricDetails->finish_dia,
            'dia_type_id' => $fabricDetails->dia_type_id,
            'gsm' => $fabricDetails->gsm,
            'unit_of_measurement_id' => $fabricDetails->unit_of_measurement_id,
        ];

        $attributes = array_merge($fabricDetails->toArray(), [
            'material_description' => $fabricDetails->fabric_description,
        ]);

        return SubGreyStoreStockSummaryReport::query()
            ->updateOrCreate($criteria, $attributes);
    }

    /**
     * @param $fabricDetails
     * @return Builder|Model
     */
    private function firstOrNewDailyStockSummary($fabricDetails)
    {
        $criteria = [
            'production_date' => $fabricDetails->challan_date,
            'sub_textile_operation_id' => $fabricDetails->sub_textile_operation_id,
            'body_part_id' => $fabricDetails->body_part_id,
            'fabric_composition_id' => $fabricDetails->fabric_composition_id,
            'fabric_type_id' => $fabricDetails->fabric_type_id,
            'color_id' => $fabricDetails->color_id,
            'ld_no' => $fabricDetails->ld_no,
            'color_type_id' => $fabricDetails->color_type_id,
            'finish_dia' => $fabricDetails->finish_dia,
            'dia_type_id' => $fabricDetails->dia_type_id,
            'gsm' => $fabricDetails->gsm,
            'unit_of_measurement_id' => $fabricDetails->unit_of_measurement_id,
        ];

        $attributes = array_merge($fabricDetails->toArray(), [
            'material_description' => $fabricDetails->fabric_description,
        ]);

        return SubGreyStoreDailyStockSummaryReport::query()
            ->updateOrCreate($criteria, $attributes);
    }
}
