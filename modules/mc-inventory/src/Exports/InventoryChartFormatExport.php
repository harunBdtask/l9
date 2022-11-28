<?php

namespace SkylarkSoft\GoRMG\McInventory\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryChartFormatExport implements WithTitle, ShouldAutoSize, FromView, ShouldQueue
{
    use Exportable;

    private $items;
    private $units;
    private $locations;
    private $loan_given_origin;
    private $loan_taken_origin;
    private $machineProfile;

    public function __construct($items,$units,$locations,$loan_given_origin,$loan_taken_origin,$machineProfile)
    {
        $this->items = $items;
        $this->units = $units;
        $this->locations = $locations;
        $this->loan_given_origin = $loan_given_origin;
        $this->loan_taken_origin = $loan_taken_origin;
        $this->machineProfile = $machineProfile;
    }

    public function title(): string
    {
        return 'Inventory Chart Format Report';
    }

    public function view(): View
    {
        $items = $this->items;
        $units = $this->units;
        $locations = $this->locations;
        $loan_given_origin = $this->loan_given_origin;
        $loan_taken_origin = $this->loan_taken_origin;
        $machineProfile = $this->machineProfile;

        return view('McInventory::machine-modules.machine-chart-format.machine-chart-format-excel', [
            'items' => $items,
            'units' => $units,
            'locations' => $locations,
            'loan_given_origin' => $loan_given_origin,
            'loan_taken_origin' => $loan_taken_origin,
            'machineProfile' => $machineProfile,
        ]);
    }
}
