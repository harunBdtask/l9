<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnIssue;

use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail ;
use SkylarkSoft\GoRMG\Inventory\Services\YarnDateWiseSummaryCalculator;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueDateWiseSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueStockSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryCalculator;

class YarnIssueStockService
{
    protected $stockService, $dateWiseStockService;
    public function __construct()
    {
        $this->stockService = new YarnIssueStockSummaryService();
        $this->dateWiseStockService=new YarnIssueDateWiseSummaryService();
    }

    /*
     * =========================/
     * Created Stock Calculation
     * =========================/
     * */
    public function created(YarnIssueDetail $yarn)
    {
        try {
            $this->updateStockSummaryWhenCreate($yarn);

            $this->updateDateWiseStockSummaryWhenCreate($yarn);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function updateStockSummaryWhenCreate($yarn)
    {
        $existingStockSummary = $this->stockService->summary($yarn);
        if ($existingStockSummary) {
            /* Update issue data for same item */
            $this->stockService->createSameItem($yarn, $existingStockSummary);
        }
    }

    private function updateDateWiseStockSummaryWhenCreate($yarn)
    {
        $existingDateWiseStockSummary = $this->dateWiseStockService->summary($yarn);
        if ($existingDateWiseStockSummary) {
            /* Update stock data for same item */
            $this->dateWiseStockService->createSameItem($yarn, $existingDateWiseStockSummary);
        }
        else {
            /* Create stock data for new item */
            $this->dateWiseStockService->create($yarn, $yarn->issue['issue_date']);
        }
    }

    /*
     * =========================/
     * Update Stock Calculation
     * =========================/
     * */
    public function updated(YarnIssueDetail $yarn)
    {
        try {
            $this->updateStockSummaryWhenUpdate($yarn);
            $this->updateDateWiseStockSummaryWhenUpdate($yarn);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function updateStockSummaryWhenUpdate($yarn)
    {
        $existingStockSummary = $this->stockService->summary($yarn);
        if ($existingStockSummary) {
            /* Update stock data for existing item */
            $this->stockService->update($yarn, $existingStockSummary);
        }
    }

    private function updateDateWiseStockSummaryWhenUpdate($yarn)
    {
        $existingDateWiseStockSummary = $this->dateWiseStockService->summary($yarn);
        if ($existingDateWiseStockSummary) {
            /* Update stock data for existing item */
            $this->dateWiseStockService->update($yarn, $existingDateWiseStockSummary);
        }
    }

    /*
     * =========================/
     * Delete Stock Calculation
     * =========================/
     * */
    public function deleted(YarnIssueDetail $yarn)
    {
        try {
            $this->updateStockSummaryWhenDelete($yarn);
            $this->updateDateWiseStockSummaryWhenDelete($yarn);
        } catch (\Exception $e) {
            return $e->getMessage();
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
