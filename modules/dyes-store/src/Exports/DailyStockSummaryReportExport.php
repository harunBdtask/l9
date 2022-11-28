<?php

namespace SkylarkSoft\GoRMG\DyesStore\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DailyStockSummaryReportExport implements ShouldAutoSize, FromView
{
    use Exportable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('dyes-store::report.dyes_stock_summary.excel', [
            'report' => $this->data
        ]);
    }
}
