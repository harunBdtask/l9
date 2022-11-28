<?php

namespace SkylarkSoft\GoRMG\Inventory\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FabricIssueViewExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $issue;
    private $type;

    public function __construct($issue,$type)
    {
        $this->issue = $issue;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Fabric Issue View';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $issue = $this->issue;
        $type = $this->type;
        return view('inventory::fabrics.pages.fabric_issue_view.excel',[
            'issue' => $issue,
            'type' => $type
        ]);
    }
}
