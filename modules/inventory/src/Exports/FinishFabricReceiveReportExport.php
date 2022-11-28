<?php

namespace SkylarkSoft\GoRMG\Inventory\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FinishFabricReceiveReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $fabricReceives;

    public function __construct($fabricReceives)
    {
        $this->fabricReceives = $fabricReceives;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Finish Fabric Receive View';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $fabricReceives = $this->fabricReceives;
        return view('inventory::reports.finish_fabric_receive_report.excel',[
            'fabricReceives' => $fabricReceives,
        ]);
    }
}
