<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport;
use SkylarkSoft\GoRMG\Misdroplets\Abstractions\FactoryWiseReportAbstraction;

class FactoryWiseInputOutputReportService extends FactoryWiseReportAbstraction
{
    protected $query;

    public function __construct()
    {
        $this->query = FinishingProductionReport::query();
    }

    public function getData(): Collection
    {
        $thisFromDate = $this->getFromDate();
        $thisToDate = $this->getToDate();
        $thisFactoryId = $this->getFactoryId();

        return $this->query->selectRaw('factory_id, SUM(sewing_input) as sewing_input, SUM(sewing_output) as sewing_output, SUM(sewing_rejection) as sewing_rejection')
            ->when($thisFactoryId, function ($query) use ($thisFactoryId) {
                $query->where('factory_id', $thisFactoryId);
            })
            ->when(($thisFromDate && $thisToDate), function ($query) use ($thisFromDate, $thisToDate) {
                $query->whereDate('production_date', '>=', $thisFromDate)
                    ->whereDate('production_date', '<=', $thisToDate);
            })
            ->groupBy('factory_id')
            ->get();
    }

    public function fetch(): SupportCollection
    {
        return $this->getData()
            ->map(function ($reportData) {
                $todayData = FinishingProductionReport::getTodayFactoryData($reportData->factory_id);
                $today_sewing_input = ($todayData && $todayData->count()) ? $todayData->sewing_input : 0;
                $total_sewing_input = $reportData->sewing_input ?? 0;
                $today_sewing_output = ($todayData && $todayData->count()) ? $todayData->sewing_output : 0;
                $total_sewing_output = $reportData->sewing_output ?? 0;
                $today_sewing_rejection = ($todayData && $todayData->count()) ? $todayData->sewing_rejection : 0;
                $total_sewing_rejection = $reportData->sewing_rejection ?? 0;
                return [
                    'factory' => $reportData->factory,
                    'today_sewing_input' => $today_sewing_input,
                    'total_sewing_input' => $total_sewing_input,
                    'today_sewing_output' => $today_sewing_output,
                    'total_sewing_output' => $total_sewing_output,
                    'today_sewing_rejection' => $today_sewing_rejection,
                    'total_sewing_rejection' => $total_sewing_rejection,
                ];
            });
    }
}
