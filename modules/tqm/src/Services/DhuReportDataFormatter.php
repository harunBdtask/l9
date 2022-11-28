<?php

namespace SkylarkSoft\GoRMG\TQM\Services;


use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\TQM\Models\TqmCuttingDhuDetails;
use SkylarkSoft\GoRMG\TQM\Models\TqmFinishingDhuDetails;
use SkylarkSoft\GoRMG\TQM\Models\TqmSewingDhuDetails;

class DhuReportDataFormatter
{
    private $type, $data, $fromDate, $toDate;

    /**
     * @param mixed $type
     */
    public function setType($type): DhuReportDataFormatter
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): DhuReportDataFormatter
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $date
     */
    public function setFromDate($date): DhuReportDataFormatter
    {
        $this->fromDate = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @param mixed $date
     */
    public function setToDate($date): DhuReportDataFormatter
    {
        $this->toDate = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    public function format(): array
    {
        $otherPercentage = 0;
        $defects = $this->defects();
        $totalDefect = $defects->sum('total_defects');
        $defectPercentagesArr = $defects->map(function ($item, $key) use ($totalDefect, &$otherPercentage) {
            if ($key > 2) {
                $otherPercentage += ($item['total_defects'] / $totalDefect) * 100;
            }
            return (double)number_format(($item['total_defects'] / $totalDefect) * 100, 2);
        });

        $data['defects_item'] = $defects;
        $data['reportData'] = $this->getData();
        $data['type'] = $this->getType();
        $data['highestDefects'] = $defects->take(3);
        $data['defectPercentagesArr'] = $defectPercentagesArr;
        $data['buyerWiseDhu'] = $this->getData()->groupBy('buyer_name');
        $data['fromDate'] = $this->getFromDate();
        $data['toDate'] = $this->getToDate();

        $data['defects'] = $defects->count() > 3 ?
            $defects->take(3)->pluck('defect_name')->push('Other')
            : $defects->pluck('defect_name');

        $data['defects_percentages'] = $defects->count() > 3 ?
            collect($defectPercentagesArr)->take(3)->push((double)number_format($otherPercentage, 2))
            : $defectPercentagesArr;

        return $data;
    }

    private function defects()
    {
        $query = TqmCuttingDhuDetails::query();

        if ($this->getType() == 'Sewing') {
            $query = TqmSewingDhuDetails::query();
        }

        if ($this->getType() == 'Finishing') {
            $query = TqmFinishingDhuDetails::query();
        }

        return $query->whereDate('production_date', '>=', $this->getFromDate())
            ->whereDate('production_date', '<=', $this->getToDate())
            ->with('tqmDefect')
            ->select('tqm_defect_id', DB::raw('SUM(total_defect) as total_defects'))
            ->groupBy('tqm_defect_id')
            ->orderBy('total_defects', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'defect_name' => $item->tqmDefect->name ?? '',
                    'total_defects' => $item->total_defects
                ];
            });
    }
}
