<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnIssueReturn;

use SkylarkSoft\GoRMG\Knitting\Models\YarnAllocationDetail;
use SkylarkSoft\GoRMG\Knitting\Models\YarnRequisitionDetail;

class KnittingProgramAllocatedQtyService
{
    private $yarn, $updatableData, $column, $type, $qty;

    public function __construct($yarn)
    {
        $this->yarn = $yarn;
    }

    /**
     * @param $column
     * @return $this
     */
    public function setColumn($column): KnittingProgramAllocatedQtyService
    {
        $this->column = $column;
        return $this;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type): KnittingProgramAllocatedQtyService
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param $qty
     * @return $this
     */
    public function setQty($qty): KnittingProgramAllocatedQtyService
    {
        $this->qty = $qty;
        return $this;
    }

    /**
     * @return $this
     */
    public function setAllocation(): KnittingProgramAllocatedQtyService
    {
        $this->updatableData = YarnAllocationDetail::query()
            ->where($this->criteria())
            ->first();
        return $this;
    }

    /**
     * @return $this
     */
    public function setRequisition(): KnittingProgramAllocatedQtyService
    {
        $this->updatableData = YarnRequisitionDetail::query()
            ->with('yarnRequisition')
            ->where('knitting_program_color_id', $this->yarn['knitting_program_color_id'])
            ->whereHas('yarnRequisition', function ($q) {
                return $q->where('requisition_no', $this->yarn['demand_no']);
            })->first();

        return $this;
    }

    public function update()
    {
        $qty = $this->type == 'created' ? $this->updatableData[$this->column] - $this->qty : $this->updatableData[$this->column] + $this->qty;

        $this->updatableData->update([
            $this->column => $qty
        ]);
    }

    /**
     * @return array
     */
    private function criteria(): array
    {
        return [
            'uom_id' => $this->yarn['uom_id'],
            'yarn_lot' => $this->yarn['yarn_lot'],
            'store_id' => $this->yarn['store_id'],
            'yarn_color' => $this->yarn['yarn_color'],
            'yarn_brand' => $this->yarn['yarn_brand'],
            'yarn_type_id' => $this->yarn['yarn_type_id'],
            'yarn_count_id' => $this->yarn['yarn_count_id'],
            'yarn_composition_id' => $this->yarn['yarn_composition_id'],
            'knitting_program_id' => $this->updatableData['yarnRequisition']['program_id'],
            'knitting_program_color_id' => $this->updatableData['knitting_program_color_id'],
        ];
    }
}
