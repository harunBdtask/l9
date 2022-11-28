<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Export;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use SkylarkSoft\GoRMG\Iedroplets\PackageConst;

class WeeklyShipmentScheduleReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    protected $reportData;

    public function __construct($data)
    {
        $this->reportData = $data;
    }

    public function view(): View
    {
        return view(PackageConst::PACKAGE_NAME . "::reports.downloads.excels.weekly_shipment_schedule_excel", $this->reportData);
    }

    public function title(): string
    {
        return "Weekly Shipment Schedule";
    }
}
