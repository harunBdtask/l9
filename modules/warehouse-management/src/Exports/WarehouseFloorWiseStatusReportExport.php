<?php

namespace SkylarkSoft\GoRMG\WarehouseManagement\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class WarehouseFloorWiseStatusReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
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
        return 'Floor Wise Status Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $data = $this->result_data;

        return view('warehouse-management::reports.downloads.excel.floor_wise_status_report_download', $data);
    }
}
