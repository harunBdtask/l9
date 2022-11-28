<?php

namespace SkylarkSoft\GoRMG\McInventory\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MaintenanceCalenderExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $maintenances;

    public function __construct($maintenances)
    {
        $this->maintenances = $maintenances;
    }

    public function title(): string
    {
        return 'Maintenance Calender';
    }

    public function view(): View
    {
        $maintenances = $this->maintenances;

        return view('McInventory::machine-modules.maintenance-calender.excel-table', [
            'maintenances' => $maintenances,
        ]);
    }

}
