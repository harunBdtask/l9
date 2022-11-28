<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyTotalReceivedFinishingExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    protected $reportData;

    public function __construct($data)
    {
        $this->reportData = $data;
    }

    public function view(): View
    {
        return view('finishingdroplets::reports.downloads.excels.monthly_total_received_finishing_download',
            $this->reportData);
    }

    public function title(): string
    {
        return 'Monthly Total Receive Finishing';
    }
}
