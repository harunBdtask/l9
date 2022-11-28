<?php

namespace SkylarkSoft\GoRMG\Inventory\Observers;

use App\Exceptions\DivisionByZeroException;
use Exception;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueDateWiseSummaryService;
use SkylarkSoft\GoRMG\Inventory\Services\YarnIssue\YarnIssueStockSummaryService;

class YarnIssueDetailObserver
{
    private $stockService;
    private $dateWiseStockService;

    public function __construct()
    {
        $this->stockService = new YarnIssueStockSummaryService();
        $this->dateWiseStockService= new YarnIssueDateWiseSummaryService();
    }

    /**
     * @throws DivisionByZeroException
     */
    public function saved(YarnIssueDetail $yarn)
    {
        try {
            $this->stockService->update($yarn, $this->stockService->summary($yarn));
        } catch (ModelNotFoundException $e) {
            $this->stockService->create($yarn);
        }
        try {
            $this->dateWiseStockService->update($yarn, $this->dateWiseStockService->summary($yarn), $yarn->issue['issue_date']);
        } catch (ModelNotFoundException $e) {
            $this->dateWiseStockService->create($yarn, $yarn->issue['issue_date']);
        }
    }
}
