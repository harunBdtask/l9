<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnIssueReturn;

use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueReturnDetail;
use SkylarkSoft\GoRMG\Inventory\YarnItemAction;

class YarnIssueReturnService
{
    protected $stockService, $dateWiseStockService;

    public function __construct()
    {
        $this->stockService = new YarnIssueReturnStockSummary();
        $this->dateWiseStockService = new YarnIssueReturnDateWiseStockSummary();
    }

    public function created(YarnIssueReturnDetail $yarn)
    {
        try {
            $this->updateStockSummaryWhenCreate($yarn);

            $this->updateDateWiseStockSummaryWhenCreate($yarn);

            $this->allocationQtyUpdateAction($yarn, 'created');

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function updateStockSummaryWhenCreate($yarn)
    {
        $existingStockSummary = $this->stockService->summary($yarn);
        if ($existingStockSummary) {
            /* Update stock data for same item */
            $this->stockService->createSameItem($yarn, $existingStockSummary);
        }
    }

    private function updateDateWiseStockSummaryWhenCreate($yarn)
    {
        $existingDateWiseStockSummary = $this->dateWiseStockService->summary($yarn);
        if ($existingDateWiseStockSummary) {
            /* Update stock data for same item */
            $this->dateWiseStockService->createSameItem($yarn, $existingDateWiseStockSummary);
        } else {
            /* Create stock data for new item */
            $this->dateWiseStockService->create($yarn);
        }
    }

    private function updateProgramAllocatedQtyWhenCreate($yarn)
    {
        $allocatedQtyService = (new KnittingProgramAllocatedQtyService($yarn))
            ->setType('created')
            ->setQty($yarn['return_qty']);

        $this->allocatedQtyUpdateAction($allocatedQtyService);
    }

    private function updateProgramAllocatedQtyWhenDelete($yarn)
    {
        $allocatedQtyService = (new KnittingProgramAllocatedQtyService($yarn))
            ->setType('deleted')
            ->setQty($yarn['return_qty']);

        $this->allocatedQtyUpdateAction($allocatedQtyService);
    }

    private function allocatedQtyUpdateAction($allocatedQtyService)
    {
        $allocatedQtyService->setRequisition()
            ->setColumn('requisition_qty')
            ->update();

        $allocatedQtyService->setAllocation()
            ->setColumn('allocated_qty')
            ->update();
    }

    public function deleted(YarnIssueReturnDetail $yarn)
    {
        try {
            $this->updateStockSummaryWhenDelete($yarn);
            $this->updateDateWiseStockSummaryWhenDelete($yarn);
            $this->allocationQtyUpdateAction($yarn, 'deleted');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function allocationQtyUpdateAction($yarn, $type)
    {
        $issueDetail = YarnIssueDetail::query()
            ->where('id', $yarn['yarn_issue_detail_id'])
            ->first();

        if ($issueDetail->demand_no) {
            $yarn['knitting_program_color_id'] = $issueDetail->requisition_color_id ?? '';
            $yarn['demand_no'] = $issueDetail->demand_no ?? '';
            $type == 'created' ? $this->updateProgramAllocatedQtyWhenCreate($yarn) : $this->updateProgramAllocatedQtyWhenDelete($yarn);
        }
    }

    private function updateStockSummaryWhenDelete($yarn)
    {
        $existingStockSummary = $this->stockService->summary($yarn);
        if ($existingStockSummary) {
            /* Update stock data for existing item */
            $this->stockService->delete($yarn, $existingStockSummary);
        }
    }

    private function updateDateWiseStockSummaryWhenDelete($yarn)
    {
        $existingDateWiseStockSummary = $this->dateWiseStockService->summary($yarn);
        if ($existingDateWiseStockSummary) {
            /* Update stock data for existing item */
            $this->dateWiseStockService->delete($yarn, $existingDateWiseStockSummary);
        }
    }
}
