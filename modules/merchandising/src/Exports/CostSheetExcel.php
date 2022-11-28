<?php

namespace SkylarkSoft\GoRMG\Merchandising\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class CostSheetExcel implements WithTitle, FromView, ShouldAutoSize
{
    use Exportable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Costing Sheet';
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $costings = $this->data;
        return view('merchandising::budget.reports.cost_breakdown_view-body', $costings);
    }
}
