<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SizeWiseFinishingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $result_data, $report_head;

    public function __construct($data, $report_head)
    {
        $this->result_data = $data;
        $this->report_head = $report_head;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Size Wise Finishing Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;
        $report_head = $this->report_head;
        return view('finishingdroplets::reports.downloads.excels.size-wise-finishing-report-download', $data, $report_head);
    }
}