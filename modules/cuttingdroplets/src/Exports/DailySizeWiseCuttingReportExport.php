<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DailySizeWiseCuttingReportExport implements WithTitle, ShouldAutoSize, FromView
{
    use Exportable;

    private $reportData;

    public function __construct($data)
    {
        $this->reportData = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Daily Size wise Cutting Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        return view('cuttingdroplets::reports.downloads.excels.daily_size_wise_cutting_report_excel', $this->reportData);
    }
}
