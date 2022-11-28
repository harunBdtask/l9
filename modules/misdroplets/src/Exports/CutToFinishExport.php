<?php

namespace SkylarkSoft\GoRMG\Misdroplets\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CutToFinishExport implements WithTitle, ShouldAutoSize, FromView
{
    use Exportable;

    private $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function view(): View
    {
        return view('misdroplets::reports.tables.cut_to_finish_report_table', ['reportData' => $this->reportData]);
    }

    public function title(): string
    {
        return "Cut To Finish Report";
    }
}
