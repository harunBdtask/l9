<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use SkylarkSoft\GoRMG\Misdroplets\Abstractions\FactoryWiseReportAbstraction;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\DateWisePrintEmbrProductionReport;

class FactoryWisePrintSendReceivedReportService extends FactoryWiseReportAbstraction
{
    protected $query;

    public function __construct()
    {
        $this->query = DateWisePrintEmbrProductionReport::query();
    }

    public function getData(): Collection
    {
        $thisFromDate = $this->getFromDate();
        $thisToDate = $this->getToDate();
        $thisFactoryId = $this->getFactoryId();

        return $this->query->with('factory:id,factory_name')->selectRaw("factory_id, SUM(print_sent_qty) as print_sent_qty, SUM(print_received_qty) as print_received_qty, SUM(print_rejection_qty) as print_rejection_qty, SUM(embroidery_sent_qty) as embroidery_sent_qty, SUM(embroidery_received_qty) as embroidery_received_qty, SUM(embroidery_rejection_qty) as embroidery_rejection_qty")
            ->when($thisFactoryId, function ($query) use ($thisFactoryId) {
                $query->where('factory_id', $thisFactoryId);
            })
            ->when(($thisFromDate && $thisToDate), function ($query) use ($thisFromDate, $thisToDate) {
                $query->whereDate('production_date', '>=', $thisFromDate)->whereDate('production_date', '<=', $thisToDate);
            })
            ->groupBy('factory_id')
            ->get();
    }

    public function fetch(): SupportCollection
    {
        return $this->getData()
            ->map(function ($reportData) {
                $todaysData = DateWisePrintEmbrProductionReport::getTodaysFactoryData($reportData->factory_id);
                $todayDataExists = $todaysData && $todaysData->count();
                return [
                    'factory' => $reportData->factory,
                    'today_print_sent_qty' => $todayDataExists ? $todaysData->print_sent_qty : 0,
                    'today_print_received_qty' => $todayDataExists ? $todaysData->print_received_qty : 0,
                    'today_print_rejection_qty' => $todayDataExists ? $todaysData->print_rejection_qty : 0,
                    'today_embroidery_sent_qty' => $todayDataExists ? $todaysData->embroidery_sent_qty : 0,
                    'today_embroidery_received_qty' => $todayDataExists ? $todaysData->embroidery_received_qty : 0,
                    'today_embroidery_rejection_qty' => $todayDataExists ? $todaysData->embroidery_rejection_qty : 0,
                    'total_print_sent_qty' => $reportData->print_sent_qty ?? 0,
                    'total_print_received_qty' => $reportData->print_received_qty ?? 0,
                    'total_print_rejection_qty' => $reportData->print_rejection_qty ?? 0,
                    'total_embroidery_sent_qty' => $reportData->embroidery_sent_qty ?? 0,
                    'total_embroidery_received_qty' => $reportData->embroidery_received_qty ?? 0,
                    'total_embroidery_rejection_qty' => $reportData->embroidery_rejection_qty ?? 0,
                ];
            });
    }
}
