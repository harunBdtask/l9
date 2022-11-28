<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class WarehouseDailyInReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
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
        return 'Daily In Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;

        return view('warehouse-management::reports.downloads.excel.daily_in_report_download', $data);
    }
}
