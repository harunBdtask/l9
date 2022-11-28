<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DateFloorWiseHourlySewingOutputExport implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $reports, $floor_id, $floors, $date, $lines, $floorwise_manual_productions, $prev_date, $prev_day;

    public function __construct($reports, $floor_id, $floors, $date, $lines, $floorwise_manual_productions, $prev_date, $prev_day)
    {
        $this->prev_date = $prev_date;
        $this->prev_day = $prev_day;
        $this->floorwise_manual_productions = $floorwise_manual_productions;
        $this->lines = $lines;
        $this->date = $date;
        $this->floors = $floors;
        $this->floor_id = $floor_id;
        $this->reports = $reports;
    }

    public function view(): View
    {
        $prev_date = $this->prev_date;
        $prev_day = $this->prev_day;
        $floorwise_manual_productions = $this->floorwise_manual_productions;
        $lines = $this->lines;
        $date = $this->date;
        $floors = $this->floors;
        $floor_id = $this->floor_id;
        $reports = $this->reports;
        return view('manual-production::reports.sewing.includes.date_floor_wise_hourly_sewing_output_inlcude',
            compact('reports',
                'prev_date', 'prev_day', 'floorwise_manual_productions', 'lines', 'date', 'floors', 'floor_id'));
    }
}
