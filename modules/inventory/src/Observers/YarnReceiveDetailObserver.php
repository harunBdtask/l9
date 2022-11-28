<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Exceptions\DateNotAvailableException;
use SkylarkSoft\GoRMG\Inventory\Exceptions\SummaryNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Models\YarnDateWiseStockSummary;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnStockSummary;
use SkylarkSoft\GoRMG\Inventory\Services\YarnDateWiseSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryCalculator;
use SkylarkSoft\GoRMG\Inventory\Services\YarnDateWiseSummaryCalculator;
use SkylarkSoft\GoRMG\Inventory\Services\YarnStockSummaryService;

class YarnReceiveDetailObserver
{
    protected $stockService, $dateWiseStockService;
    public function __construct()
    {
        $this->stockService = new YarnStockSummaryCalculator();
        $this->dateWiseStockService=new YarnDateWiseSummaryCalculator();
    }

    public function created(YarnReceiveDetail $yarn)
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
            /* Update stock data for same item */
            $this->stockService->createSameItem($yarn, $existingStockSummary);
        }
        else {
            /* Create stock data for new item */
            $this->stockService->create($yarn);
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
            $this->dateWiseStockService->create($yarn, $yarn->yarnReceive['receive_date']);
        }
    }

    public function updated(YarnReceiveDetail $yarn)
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
}
