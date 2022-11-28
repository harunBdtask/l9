<?php

namespace SkylarkSoft\GoRMG\Inventory\Exports\TrimsStore;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class TrimsStoreReceiveReportExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
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
        return 'Trims Receive Report';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $receive = $this->reportData;

        return view('inventory::trims-store.trims-receive.excel', compact('receive'));
    }
}
