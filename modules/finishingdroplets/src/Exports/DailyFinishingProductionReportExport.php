<?php

namespace SkylarkSoft\GoRMG\Finishingdroplets\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DailyFinishingProductionReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
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
        return 'Production Report '.date('d-M-Y', strtotime($this->result_data['date']));
    }


    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;
        return view('finishingdroplets::reports.downloads.excels.daily_finishing_production_report_download', $data);
    }
}