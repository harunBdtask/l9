<?php

namespace SkylarkSoft\GoRMG\DyesStore\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DyesAndChemicalReceiveReportExport implements ShouldAutoSize, FromView
{
    use Exportable;

    private $dyesChemicalReceive;

    public function __construct($dyesChemicalReceive)
    {
        $this->dyesChemicalReceive = $dyesChemicalReceive;
    }

    public function view(): View
    {
        return view('dyes-store::report.dyes_and_chemical_receive_report.excel', [
            'dyesChemicalReceive' => $this->dyesChemicalReceive
        ]);
    }
}
