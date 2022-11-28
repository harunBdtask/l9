<?php


namespace SkylarkSoft\GoRMG\Inventory\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FinishFabricIssueReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $fabricIssues;

    public function __construct($fabricIssues)
    {
        $this->fabricIssues = $fabricIssues;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Finish Fabric Issue View';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $fabricIssues = $this->fabricIssues;
        return view('inventory::reports.finish_fabric_issue_report.excel',[
            'fabricIssues' => $fabricIssues,
        ]);
    }
}
