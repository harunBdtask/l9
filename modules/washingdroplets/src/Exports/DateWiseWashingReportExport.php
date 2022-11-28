<?php

namespace SkylarkSoft\GoRMG\Washingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DateWiseWashingReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $result_data;

    public function __construct($data)
    {
        $this->result_data = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Date Wise Washing Report';
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $washing_report = $this->result_data;
        return view('washingdroplets::reports.downloads.excels.date-wise-washing-report-download', $washing_report);
    }
}