<?php

namespace SkylarkSoft\GoRMG\ManualProduction\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DailyInputUnitWiseReportExport implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $floors, $reports;

    public function __construct($reports, $floors)
    {
        $this->floors = $floors;
        $this->reports = $reports;

    }

    public function view(): View
    {
        $floors = $this->floors;
        $reports = $this->reports;
        return view('manual-production::reports.sewing.includes.daily_input_unit_wise_report_inlcude',
            compact('floors', 'reports'));
    }
}
