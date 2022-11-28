<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport;
use SkylarkSoft\GoRMG\Misdroplets\Abstractions\FactoryWiseReportAbstraction;

class FactoryWiseCuttingReportService extends FactoryWiseReportAbstraction
{
    protected $query;

    public function __construct()
    {
        $this->query = DateTableWiseCutProductionReport::query();
    }

    public function getData(): Collection
    {
        $thisFromDate = $this->getFromDate();
        $thisToDate = $this->getToDate();
        $thisFactoryId = $this->getFactoryId();

        return $this->query->selectRaw("factory_id, SUM(cutting_qty) as cutting_qty, SUM(cutting_rejection_qty) as cutting_rejection_qty")
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
        $thisFromDate = $this->getFromDate();
        $thisToDate = $this->getToDate();

        return $this->getData()
            ->map(function ($reportData) use ($thisFromDate, $thisToDate) {
                $factory_today_cutting_data = DateTableWiseCutProductionReport::todaysFactoryCutting($reportData->factory_id);
                $todays_cutting = $factory_today_cutting_data ? $factory_today_cutting_data->cutting_qty : 0;
                $todays_cutting_rejection = $factory_today_cutting_data ? $factory_today_cutting_data->cutting_rejection_qty : 0;
                $todays_ok_cutting = $todays_cutting - $todays_cutting_rejection;
                $total_cutting = $reportData->cutting_qty;
                $total_cutting_rejection = $reportData->cutting_rejection_qty;
                $total_ok_cutting = $total_cutting - $total_cutting_rejection;
                $cutting_target = DateTableWiseCutProductionReport::dateRangeWiseCuttingTarget($reportData->factory_id, $thisFromDate, $thisToDate);
                $achievement = $cutting_target > 0 ? ($total_ok_cutting * 100 / $cutting_target) : 0;
                return [
                    'factory' => $reportData->factory,
                    'todays_cutting' => $todays_cutting,
                    'todays_cutting_rejection' => $todays_cutting_rejection,
                    'todays_ok_cutting' => $todays_ok_cutting,
                    'total_cutting' => $total_cutting,
                    'total_cutting_rejection' => $total_cutting_rejection,
                    'total_ok_cutting' => $total_ok_cutting,
                    'cutting_target' => $cutting_target,
                    'achievement' => $achievement,
                ];
            });
    }
}
