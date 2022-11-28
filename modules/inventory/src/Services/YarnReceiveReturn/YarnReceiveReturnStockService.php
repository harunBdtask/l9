<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnReceiveReturn;

use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveReturnDetail;

class YarnReceiveReturnStockService
{
    protected $stockService, $dateWiseStockService;

    public function __construct()
    {
        $this->stockService = new YarnReceiveReturnStockSummaryService();
        $this->dateWiseStockService = new YarnReceiveReturnDateWiseSummaryService();
    }

    /*
     * =========================/
     * Created Stock Calculation
     * =========================/
     * */
    public function created(YarnReceiveReturnDetail $yarn)
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
        $yarnStockSummary = $this->stockService->summary($yarn);
        if ($yarnStockSummary) {
            /* Update stock data for same item & date */
            $this->stockService->createSameItem($yarn, $yarnStockSummary);
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

    /*
     * =========================/
     * Update Stock Calculation
     * =========================/
     * */
    public function updated(YarnReceiveReturnDetail $yarn)
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
        $yarnStockSummary = $this->stockService->summary($yarn);
        if ($yarnStockSummary) {
            /* Update stock data for existing item */
            $this->stockService->update($yarn, $yarnStockSummary);
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
    public function deleted(YarnReceiveReturnDetail $yarn)
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
        $yarnStockSummary = $this->stockService->summary($yarn);
        if ($yarnStockSummary) {
            /* Update stock data for existing item */
            $this->stockService->delete($yarn, $yarnStockSummary);
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
