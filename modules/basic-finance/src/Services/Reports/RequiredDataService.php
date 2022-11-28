<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Reports;

use SkylarkSoft\GoRMG\Merchandising\Services\Month\MonthService;
use SkylarkSoft\GoRMG\SystemSettings\Models\AssigningFactory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;

class RequiredDataService
{
    protected $data;
    protected $factoryId;
    protected $fromDate;
    protected $toDate;
    protected $year;
    protected $month;
    protected $requiredData;

    public function __construct(ReportViewService $reportViewService) {
        $this->factoryId = $reportViewService->getFactoryId();
        $this->fromDate = $reportViewService->getFromDate();
        $this->toDate = $reportViewService->getToDate();
        $this->year = $reportViewService->getYear();
        $this->month = $reportViewService->getMonth();
        $this->requiredData = $reportViewService->getRequiredData();
    }
    public function getInfo(): array
    {
        $requiredData = array();
        if (in_array('factory', $this->requiredData)) {
            $requiredData['factory'] = Factory::query()->where('id', $this->factoryId)->first()->factory_name ?? null;
        }

        if (in_array('year', $this->requiredData)) {
            $requiredData['year'] = $this->year ?? null;
        }

        if (in_array('month', $this->requiredData)) {
            $requiredData['month'] = collect(MonthService::months())
                    ->where('id', $this->month)
                    ->first()['text'] ?? null;
        }

        if (in_array('from_date', $this->requiredData) ) {
            $fromDate = $this->fromDate ? date("F j, Y", strtotime($this->fromDate)) : null;
            $toDate = $this->toDate ? date("F j, Y", strtotime($this->toDate)) : null;
            $toDate = $toDate ? ' - ' . $toDate : '';
            $requiredData['date'] = $fromDate . $toDate;
        }

        return $requiredData;
    }

}
